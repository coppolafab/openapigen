<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Paths implements JsonSerializable
{
    public function __construct(private array $paths)
    {
    }

    public function jsonSerialize(): array
    {
        return $this->paths;
    }
}
