<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Callback
{
    public function __construct(private ?array $callbacks = null)
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['callbacks'] ?? null,
        );
    }
}
