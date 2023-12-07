<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class SecurityScheme
{
    public function __construct(
        private SecuritySchemeType $type,
        private string $name,
        private ApiKeyLocation $in,
        private string $scheme,
        private OAuthFlows $flows,
        private string $openIdConnectUrl,
        private ?string $description = null,
        private ?string $bearerFormat = null,
    ) {
    }
}
