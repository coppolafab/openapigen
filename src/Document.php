<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Document implements JsonSerializable
{
    public function __construct(private OpenApi $root)
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['root'],
        );
    }

    public function jsonSerialize(): array
    {
        return $this->root->jsonSerialize();
    }
}
