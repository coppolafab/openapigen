<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Coppolafab\OpenApi\Attributes as OA;
use Coppolafab\OpenApi\Components;
use JsonSerializable;
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

                if (preg_match('/@return\s+array{\s*(.*)\s*}/', $docBlock, $matches, PREG_UNMATCHED_AS_NULL) === false) {
                    continue;
                }

                if (preg_match_all("/'([^']+)':\s*(\??\w+(?:\[\]|<[^>]+>)?)/", $matches[1], $arrayFields, PREG_UNMATCHED_AS_NULL) === false) {
                    continue;
                }

                array_shift($arrayFields);
                $properties = [];
                $required = [];
                $lenght = count($arrayFields[0]);
                $i = 0;

                for ($i = 0; $i < $lenght; $i++) {
                    $propertyName = $arrayFields[0][$i];
                    $typePart = $arrayFields[1][$i];
                    $property = self::mapType($typePart);
                    $properties[$propertyName] = $property;
                    $required[] = $propertyName;
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

    private static function mapType(string $typePart): array
    {
        $property = match($typePart) {
            'null' => ['type' => 'null'],
            'bool', '?bool', 'true', 'false' => ['type' => 'boolean'],
            'bool[]' => ['type' => 'array', 'items' => ['type' => 'boolean']],
            'int', '?int' => ['type' => 'integer'],
            'int[]' => ['type' => 'array', 'items' => ['type' => 'integer']],
            'float', '?float' => ['type' => 'number'],
            'float[]' => ['type' => 'array', 'items' => ['type' => 'number']],
            'string', '?string' => ['type' => 'string'],
            'string[]' => ['type' => 'array', 'items' => ['type' => 'string']],
            default => null,
        };

        $nullable = $typePart === 'null' || str_starts_with($typePart, '?');

        if ($property) {
            $property['nullable'] = $nullable;

            return $property;
        }

        $property = ['unknown' => $typePart, 'nullable' => $nullable];

        if (str_starts_with($typePart, 'array<') || str_ends_with($typePart, '[]')) {
            $property['type'] = 'array';
        }

        return $property;
    }
}
