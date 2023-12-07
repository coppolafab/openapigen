<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Link
{
    public function __construct(
        private ?string $operationRef = null,
        private ?string $operationId = null,
        private ?array $parameters = null,
        private mixed $requestBody = null,
        private ?string $description = null,
        private ?Server $server = null,
    ) {
    }
}
