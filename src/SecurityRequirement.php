<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class SecurityRequirement implements JsonSerializable
{
    public function __construct(private array $schemes = [])
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self($properties['schemes'] ?? []);
    }

    public function getSchemes(): array
    {
        return $this->schemes;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return $this->schemes;
    }
}
