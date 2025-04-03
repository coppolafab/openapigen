<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Link
{
    public function __construct(
        private ?string $operationRef = null,
        private ?string $operationId = null,
        private ?array $parameters = null,
        private mixed $requestBody = null,
        private ?string $description = null,
        private ?Server $server = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['operationRef'] ?? null,
            $properties['operationId'] ?? null,
            $properties['parameters'] ?? null,
            $properties['requestBody'] ?? null,
            $properties['description'] ?? null,
            $properties['server'] ?? null,
        );
    }
}
