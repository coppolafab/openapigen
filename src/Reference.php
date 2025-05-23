<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class Reference implements JsonSerializable
{
    public function __construct(
        private string $ref,
        private ?string $summary = null,
        private ?string $description = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['ref'],
            $properties['summary'] ?? null,
            $properties['description'] ?? null,
        );
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $reference = [
            '$ref' => $this->ref,
        ];

        if ($this->summary !== null) {
            $reference['summary'] = $this->summary;
        }

        if ($this->description !== null) {
            $reference['description'] = $this->description;
        }

        return $reference;
    }
}
