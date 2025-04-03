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

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['authorizationUrl'],
            $properties['tokenUrl'],
            $properties['scopes'],
            $properties['refreshUrl'] ?? null,
        );
    }
}
