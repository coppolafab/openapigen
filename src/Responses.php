<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Responses implements JsonSerializable
{
    public function __construct(private ?array $responses = null)
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self($properties['responses'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return $this->responses;
    }
}
