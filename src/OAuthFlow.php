<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class OAuthFlow
{
    public function __construct(
        private string $authorizationUrl,
        private string $tokenUrl,
        private array $scopes,
        private ?string $refreshUrl = null,
    ) {
    }
}
