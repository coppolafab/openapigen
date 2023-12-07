<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class ExternalDocumentation
{
    public function __construct(private string $url, private ?string $description = null)
    {
    }
}
