<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Example implements JsonSerializable
{
    public function __construct(
        private ?string $summary = null,
        private ?string $description = null,
        public mixed $value = null,
        private ?string $externalValue = null,
    ) {
        if ($value !== null && $externalValue !== null) {
            throw new InvalidArgumentException('The value and externalValue fields are mutually exclusive');
        }
    }

    public function jsonSerialize(): array
    {
        $example = [];

        if ($this->summary !== null) {
            $example['summary'] = $this->summary;
        }

        if ($this->description !== null) {
            $example['description'] = $this->description;
        }

        if ($this->value !== null) {
            $example['value'] = $this->value;
        }

        if ($this->externalValue !== null) {
            $example['externalValue'] = $this->externalValue;
        }

        return $example;
    }
}
