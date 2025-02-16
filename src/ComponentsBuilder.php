<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Coppolafab\OpenApi\Attributes as OA;
use Coppolafab\OpenApi\Components;
use JsonSerializable;
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
use ReflectionMethod;

use function array_replace_recursive;

final readonly class ComponentsBuilder
{
    public static function build(array $classMap, array $componentsConfigs = []): Components
    {
        return new Components(
            self::buildSchemas($classMap, $componentsConfigs['schemas'] ?? []),
            $componentsConfigs['responses'] ?? null,
            $componentsConfigs['parameters'] ?? null,
            $componentsConfigs['examples'] ?? null,
            $componentsConfigs['requestBodies'] ?? null,
            $componentsConfigs['headers'] ?? null,
            $componentsConfigs['securitySchemes'] ?? null,
            $componentsConfigs['links'] ?? null,
            $componentsConfigs['callbacks'] ?? null,
            $componentsConfigs['pathItems'] ?? null,
        );
    }

    private static function buildSchemas(array $classMap, array $schemasReplacements): ?array
    {
        $config = new ParserConfig(usedAttributes: []);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

        $schemaInfos = [];

        foreach (array_keys($classMap) as $class) {
            $reflector = new ReflectionClass($class);

            if ($reflector->isAbstract()) {
                continue;
            }

            $schemaAttributes = $reflector->getAttributes(OA\Schema::class);

            if (! $schemaAttributes) {
                continue;
            }

            $schemaAttrInstance = $schemaAttributes[0]->newInstance();
            $schemaName = null;
            $schema = null;

            if ($schemaAttrInstance->schema) {
                $schemaName = $schemaAttrInstance->name;
                $schema = $schemaAttrInstance->schema;
            } else if (! $reflector->implementsInterface(JsonSerializable::class)) {
                continue;
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

                if ($returnTagType->kind !== 'array') {
                    continue;
                }

                $properties = [];
                $required = [];

                foreach ($returnTagType->items as $item) {
                    $propertyName = $item->keyName->value;
                    $property = self::mapType($item->valueType);
                    $properties[$propertyName] = $property;

                    if (! $item->optional) {
                        $required[] = $propertyName;
                    }
                }

                $schema = [
                    'type' => 'object',
                    'properties' => $properties,
                ];

                if ($required) {
                    $schema['required'] = $required;
                }

                $schemaName = $reflector->getShortName();
            }

            $schemaInfos[$schemaName] = [
                'schemaName' => $schemaName,
                'schema' => $schema,
            ];
        }

        if (! $schemaInfos && ! $schemasReplacements) {
            return null;
        }

        foreach ($schemaInfos as $schemaInfo) {
            $schema = $schemaInfo['schema'];

            foreach ($schema['properties'] as $propertyName => $property) {
                if (isset($property['unknown'])) {
                    if (! isset($property['type']) && isset($schemaInfos[$property['unknown']])) {
                        $schema['properties'][$propertyName] = ['$ref' => '#/components/schemas/' . $property['unknown']];
                    } elseif (isset($property['type']) && ! isset($property['items']) && isset($schemaInfos[$property['unknown']])) {
                        $schema['properties'][$propertyName]['items'] = ['$ref' => '#/components/schemas/' . $property['unknown']];
                    } else {
                        $schema['properties'][$propertyName] = ['type' => 'object'];
                    }

                    unset($property['unknown']);
                }
            }

            $schemas[$schemaInfo['schemaName']] = $schema;
        }

        return array_replace_recursive($schemas, $schemasReplacements);
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
        }
            
        $property['nullable'] = true;

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
