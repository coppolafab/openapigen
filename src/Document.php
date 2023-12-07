<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Document implements JsonSerializable
{
    public function __construct(private OpenApi $root)
    {
    }

    public function jsonSerialize(): array
    {
        return $this->root->jsonSerialize();
    }
}
