<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class ExternalDocumentation
{
    public function __construct(private string $url, private ?string $description = null)
    {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['url'],
            $properties['description'] ?? null,
        );
    }
}
