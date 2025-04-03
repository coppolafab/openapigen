<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class ServerVariable
{
    public function __construct(
        private string $default,
        private ?array $enum = null,
        private ?string $description = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['default'],
            $properties['enum'] ?? null,
            $properties['description'] ?? null,
        );
    }
}
