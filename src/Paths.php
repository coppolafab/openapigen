<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class Paths implements JsonSerializable
{
    public function __construct(private array $paths)
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self($properties['paths']);
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return $this->paths;
    }
}
