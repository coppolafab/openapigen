<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Coppolafab\OpenApi\Attributes as OA;
use Coppolafab\OpenApi\Components;
use JsonSerializable;
use Nette\Utils\Reflection;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;

use function array_column;
use function array_keys;
use function array_map;
use function array_replace_recursive;
use function array_unique;
use function enum_exists;
use function in_array;
use function is_array;
use function ksort;

final readonly class ComponentsBuilder
{
    public static function build(array $classMap, array $componentsConfigs = []): Components
    {
        $schemasExamples = [];

        return new Components(
            self::buildSchemas($classMap, $componentsConfigs['schemas'] ?? [], $schemasExamples),
            $componentsConfigs['responses'] ?? null,
            $componentsConfigs['parameters'] ?? null,
            $componentsConfigs['examples'] ?? $schemasExamples,
            $componentsConfigs['requestBodies'] ?? null,
            $componentsConfigs['headers'] ?? null,
            $componentsConfigs['securitySchemes'] ?? null,
            $componentsConfigs['links'] ?? null,
            $componentsConfigs['callbacks'] ?? null,
            $componentsConfigs['pathItems'] ?? null,
        );
    }

    private static function buildSchemas(array $classMap, array $schemasReplacements, array &$schemasExamples = []): ?array
    {
        $config = new ParserConfig(usedAttributes: []);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

        $schemaInfos = [];

        foreach (array_keys($classMap) as $class) {
            $reflector = new ReflectionClass($class);
            $schemaAttributes = $reflector->getAttributes(OA\Schema::class);

            if (! $schemaAttributes) {
                continue;
            }

            $schemaAttrInstance = $schemaAttributes[0]->newInstance();
            $schemaName = $schemaAttrInstance->name ?? $reflector->getShortName();
            $schema = null;

            if ($schemaAttrInstance->schema) {
                $schema = $schemaAttrInstance->schema;
            } elseif ($reflector->isAbstract()) {
                continue;
            } elseif (! $reflector->implementsInterface(JsonSerializable::class)) {
                if ($reflector->isEnum()) {
                    $reflectionEnum = new ReflectionEnum($reflector->getName());
                    $type = $reflectionEnum->getBackingType()->getName() === 'int' ? 'integer' : 'string';
                    $cases = array_column($reflector->getName()::cases(), 'value');

                    $schema = [
                        'type' => $type,
                        'enum' => $cases,
                    ];
                } else {
                    continue;
                }
            } else {
                $method = new ReflectionMethod($reflector->getName(), 'jsonSerialize');
                $docBlock = $method->getDocComment();

                if ($docBlock === false) {
                    continue;
                }

                $tokens = new TokenIterator($lexer->tokenize($docBlock));
                $phpDocNode = $phpDocParser->parse($tokens);
                $returnTags = $phpDocNode->getReturnTagValues();

                if (! $returnTags) {
                    continue;
                }

                $returnTag = $returnTags[0];
                $returnTagType = $returnTag->type;

                if ($returnTagType->kind !== 'array' && $returnTagType->kind !== 'non-empty-array') {
                    continue;
                }

                $properties = [];
                $required = [];

                foreach ($returnTagType->items as $item) {
                    $propertyName = $item->keyName->value;
                    $property = self::mapType($item->valueType);

                    if (! $item->optional) {
                        $required[] = $propertyName;
                    }

                    if (isset($property['type']) && ($property['type'] === 'string' || is_array($property['type']) && in_array('string', $property['type'], true))) {
                        $reflectionProperty = null;

                        try {
                            $reflectionProperty = $reflector->getProperty($propertyName);
                        } catch (ReflectionException) {
                        }

                        if ($reflectionProperty) {
                            $propertyAttributes = $reflectionProperty->getAttributes(OA\Schema\Property::class);

                            if ($propertyAttributes) {
                                $propertyAttrInstance = $propertyAttributes[0]->newInstance();

                                if ($propertyAttrInstance->format) {
                                    $property['format'] = $propertyAttrInstance->format;
                                }
                            }
                        }
                    }

                    $properties[$propertyName] = $property;
                }

                $schema = [
                    'type' => 'object',
                    'properties' => $properties,
                    'additionalProperties' => $schemaAttrInstance->additionalProperties,
                ];

                if ($required) {
                    $schema['required'] = $required;
                }
            }

            $schemaInfos[$schemaName] = [
                'schemaName' => $schemaName,
                'schema' => $schema,
                'classReflector' => $reflector,
                'examples' => $schemaAttrInstance->examples,
            ];
        }

        if (! $schemaInfos && ! $schemasReplacements) {
            return null;
        }

        foreach ($schemaInfos as $schemaInfo) {
            $schema = $schemaInfo['schema'];

            if (isset($schema['properties'])) {
                foreach ($schema['properties'] as $propertyName => $property) {
                    if (isset($property['unknown'])) {
                        $unknownClass = $property['unknown'];
                        unset($property['unknown']);

                        if (isset($schemaInfos[$unknownClass])) {
                            if (! isset($property['type'])) {
                                $schema['properties'][$propertyName] = ['$ref' => '#/components/schemas/' . $unknownClass];
                            } elseif (is_array($property['type'])) {
                                $schema['properties'][$propertyName] = ['anyOf' => [
                                    $property,
                                    ['$ref' => '#/components/schemas/' . $unknownClass],
                                ]];
                            }
                        } else {
                            $expandedClassName = Reflection::expandClassName($unknownClass, $schemaInfo['classReflector']);

                            if ($expandedClassName && enum_exists($expandedClassName)) {
                                $reflectionEnum = new ReflectionEnum($expandedClassName);
                                $type = $reflectionEnum->getBackingType()->getName() === 'int' ? 'integer' : 'string';
                                $cases = array_column($expandedClassName::cases(), 'value');
                                $schema['properties'][$propertyName] = ['type' => $type, 'enum' => $cases];
                            } else {
                                $schema['properties'][$propertyName] = ['type' => 'object'];
                            }
                        }
                    }

                    if (isset($property['type']) && $property['type'] === 'array' && isset($property['items'], $property['items']['unknown'])) {
                        if (isset($schemaInfos[$property['items']['unknown']])) {
                            $schema['properties'][$propertyName]['items'] = ['$ref' => '#/components/schemas/' . $property['items']['unknown']];
                        } else {
                            $expandedClassName = Reflection::expandClassName($property['items']['unknown'], $schemaInfo['classReflector']);

                            if ($expandedClassName && enum_exists($expandedClassName)) {
                                $reflectionEnum = new ReflectionEnum($expandedClassName);
                                $type = $reflectionEnum->getBackingType()->getName() === 'int' ? 'integer' : 'string';
                                $cases = array_column($expandedClassName::cases(), 'value');
                                $schema['properties'][$propertyName]['items'] = ['type' => $type, 'enum' => $cases];
                            } else {
                                $schema['properties'][$propertyName]['items'] = ['type' => 'object'];
                            }
                        }

                        unset($property['items']['unknown']);
                    }
                }
            }

            $schemas[$schemaInfo['schemaName']] = $schema;

            foreach ($schemaInfo['examples'] as $name => $example) {
                $schemasExamples[$name] = $example;
            }
        }

        $schemas = array_replace_recursive($schemas, $schemasReplacements);
        ksort($schemas);

        return $schemas;
    }

    private static function mapType(ArrayTypeNode|GenericTypeNode|IdentifierTypeNode|NullableTypeNode|UnionTypeNode $valueType): array
    {
        if ($valueType instanceof NullableTypeNode) {
            $property = self::mapNullableType($valueType);
        } elseif ($valueType instanceof GenericTypeNode) {
            $property = self::mapType($valueType->type);
            $property['items'] = self::mapType($valueType->genericTypes[1]);
        } elseif ($valueType instanceof UnionTypeNode) {
            $types = [];

            foreach (array_map(fn ($type) => self::mapType($type), $valueType->types) as $type) {
                $types[] = $type['type'];
            }

            $property = ['type' => array_unique($types)];
        } elseif ($valueType instanceof ArrayTypeNode) {
            $property = self::mapArrayType($valueType);
        } elseif ($valueType instanceof IdentifierTypeNode) {
            $property = self::mapIdentifierType($valueType);
        } else {
            $property = ['type' => 'object'];
        }

        return $property;
    }

    private static function mapNullableType(NullableTypeNode $typeNode): array
    {
        $property = self::mapType($typeNode->type);

        if (isset($property['type'])) {
            if (is_array($property['type']) && ! in_array('null', $property['type'], true)) {
                $property['type'][] = 'null';
            } elseif (! is_array($property['type']) && $property['type'] !== 'null') {
                $property['type'] = [$property['type'], 'null'];
            }
        } else {
            $property['type'] = ['null'];
        }

        return $property;
    }

    private static function mapArrayType(ArrayTypeNode $typeNode): array
    {
        return [
            'type' => 'array',
            'items' => self::mapType($typeNode->type),
        ];
    }

    private static function mapIdentifierType(IdentifierTypeNode $typeNode): array
    {
        $name = $typeNode->name;

        $property = match($name) {
            'null' => ['type' => 'null'],
            'bool', 'true', 'false' => ['type' => 'boolean'],
            'int' => ['type' => 'integer'],
            'float' => ['type' => 'number'],
            'string' => ['type' => 'string'],
            'mixed' => ['type' => 'object'],
            'array' => ['type' => 'array'],
            default => ['unknown' => $name],
        };

        return $property;
    }
}
