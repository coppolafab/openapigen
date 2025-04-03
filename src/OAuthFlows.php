<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class OAuthFlows
{
    public function __construct(
        private ?OAuthFlow $implicit = null,
        private ?OAuthFlow $password = null,
        private ?OAuthFlow $clientCredentials = null,
        private ?OAuthFlow $authorizationCode = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['implicit'] ?? null,
            $properties['password'] ?? null,
            $properties['clientCredentials'] ?? null,
            $properties['authorizationCode'] ?? null,
        );
    }
}
