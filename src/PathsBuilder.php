<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Coppolafab\OpenApi\Attributes as OA;
use ReflectionClass;
use ReflectionMethod;

use function array_keys;

final readonly class PathsBuilder
{
    public static function build(array $classMap, ?Components $components = null, array $paths = []): Paths
    {
        foreach (array_keys($classMap) as $class) {
            $reflector = new ReflectionClass($class);

            if ($reflector->isAbstract() || ! $reflector->getAttributes(OA\Paths::class)) {
                continue;
            }

            $classPathItemAttrs = $reflector->getAttributes(OA\PathItem::class);

            foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isAbstract() || $method->isConstructor() || $method->isDestructor() || $method->isStatic()) {
                    continue;
                }

                $methodPathItems = $method->getAttributes(OA\PathItem::class);
                $pathItemAttrs = $methodPathItems ? $methodPathItems : $classPathItemAttrs;

                $operationAttrs = $method->getAttributes(OA\Operation::class);

                foreach ($pathItemAttrs as $pathItemAttr) {
                    $pathItemAttrInstance = $pathItemAttr->newInstance();

                    if ($pathItemAttrInstance->ref !== null) {
                        $paths[$pathItemAttrInstance->path] = $components['pathItems'][$pathItemAttrInstance->ref];
                    } elseif (! isset($paths[$pathItemAttrInstance->path])) {
                        $paths[$pathItemAttrInstance->path] = new PathItem(
                            null,
                            $pathItemAttrInstance->summary,
                            $pathItemAttrInstance->description,
                        );
                    }

                    $pathItem = $paths[$pathItemAttrInstance->path];

                    foreach ($operationAttrs as $operationAttr) {
                        $operationAttrInstance = $operationAttr->newInstance();

                        $operation = new Operation(
                            tags: $operationAttrInstance->tags,
                            summary: $operationAttrInstance->summary,
                            description: $operationAttrInstance->description,
                            operationId: $operationAttrInstance->operationId,
                            parameters: $operationAttrInstance->parameters,
                            requestBody: $operationAttrInstance->requestBody,
                            responses: $operationAttrInstance->responses,
                            deprecated: $operationAttrInstance->deprecated,
                            security: $operationAttrInstance->security,
                        );

                        match ($operationAttrInstance->verb) {
                            OperationVerb::GET => $pathItem->setGet($operation),
                            OperationVerb::PUT => $pathItem->setPut($operation),
                            OperationVerb::POST => $pathItem->setPost($operation),
                            OperationVerb::DELETE => $pathItem->setDelete($operation),
                            OperationVerb::OPTIONS => $pathItem->setOptions($operation),
                            OperationVerb::HEAD => $pathItem->setHead($operation),
                            OperationVerb::PATCH => $pathItem->setPatch($operation),
                            OperationVerb::TRACE => $pathItem->setTrace($operation),
                        };
                    }
                }
            }
        }

        return new Paths($paths);
    }
}
