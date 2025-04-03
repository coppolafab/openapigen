<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Encoding
{
    public function __construct(
        private ?string $contentType = null,
        private ?array $headers = null,
        private ?StyleValue $style = null,
        private bool $explode = false,
        private bool $allowReserved = false,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['contentType'] ?? null,
            $properties['headers'] ?? null,
            $properties['style'] ?? null,
            $properties['explode'] ?? false,
            $properties['allowReserved'] ?? false,
        );
    }
}
