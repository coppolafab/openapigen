<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Tag
{
    public function __construct(
        private string $name,
        private ?string $description = null,
        private ?ExternalDocumentation $externalDocs = null,
    ) {
    }
}
