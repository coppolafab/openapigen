<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class Parameter implements JsonSerializable
{
    public function __construct(
        private string $name,
        private ParameterLocation $in,
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
            $properties['name'],
            $properties['in'],
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getIn(): ParameterLocation
    {
        return $this->in;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function getSchema(): mixed
    {
        return $this->schema;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $parameter = [
            'name' => $this->name,
            'in' => $this->in,
        ];

        if ($this->description !== null) {
            $parameter['description'] = $this->description;
        }

        $parameter['required'] = $this->required;
        $parameter['deprecated'] = $this->deprecated;
        $parameter['allowEmptyValue'] = $this->allowEmptyValue;

        if ($this->style !== null) {
            $parameter['style'] = $this->style;
        }

        $parameter['explode'] = $this->explode;
        $parameter['allowReserved'] = $this->allowReserved;

        if ($this->schema !== null) {
            $parameter['schema'] = $this->schema;
        }

        if ($this->example !== null) {
            $parameter['example'] = $this->example;
        }

        if ($this->examples !== null) {
            $parameter['examples'] = $this->examples;
        }

        if ($this->content !== null) {
            $parameter['content'] = $this->content;
        }

        return $parameter;
    }
}
