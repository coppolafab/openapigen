<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class SecurityRequirement implements JsonSerializable
{
    public function __construct(private array $schemes = [])
    {
    }

    public function jsonSerialize(): array
    {
        return $this->schemes;
    }
}
