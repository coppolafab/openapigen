<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class ServerVariable
{
    public function __construct(
        private string $default,
        private ?array $enum = null,
        private ?string $description = null,
    ) {
    }
}
