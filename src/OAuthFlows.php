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
}
