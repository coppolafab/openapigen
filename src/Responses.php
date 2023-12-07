<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Responses implements JsonSerializable
{
    public function __construct(private ?array $responses = null)
    {
    }

    public function jsonSerialize(): array
    {
        return $this->responses;
    }
}
