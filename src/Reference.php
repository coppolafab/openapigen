<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Reference implements JsonSerializable
{
    public function __construct(
        private string $ref,
        private ?string $summary = null,
        private ?string $description = null,
    ) {
    }

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
