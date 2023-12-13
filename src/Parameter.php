<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

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
