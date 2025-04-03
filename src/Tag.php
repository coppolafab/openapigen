<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Tag
{
    public function __construct(
        private string $name,
        private ?string $description = null,
        private ?ExternalDocumentation $externalDocs = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['name'],
            $properties['description'] ?? null,
            $properties['externalDocs'] ?? null,
        );
    }
}
