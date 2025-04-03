<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Header implements JsonSerializable
{
    public function __construct(
        private ?string $description = null,
        private bool $required = false,
        private bool $deprecated = false,
        private bool $allowEmptyValue = false,
        private ?string $style = null,
        private bool $explode = false,
        private bool $allowReserved = false,
        private mixed $schema = null,
        private mixed $example = null,
        private ?array $examples = null,
        private ?array $content = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['description'] ?? null,
            $properties['required'] ?? false,
            $properties['deprecated'] ?? false,
            $properties['allowEmptyValue'] ?? false,
            $properties['style'] ?? null,
            $properties['explode'] ?? false,
            $properties['allowReserved'] ?? false,
            $properties['schema'] ?? null,
            $properties['example'] ?? null,
            $properties['examples'] ?? null,
            $properties['content'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        $header = [];

        if ($this->description !== null) {
            $header['description'] = $this->description;
        }

        if ($this->schema !== null) {
            $header['schema'] = $this->schema;
        }

        if ($this->example !== null) {
            $header['example'] = $this->example;
        }

        if ($this->examples !== null) {
            $header['examples'] = $this->examples;
        }

        return $header;
    }
}
