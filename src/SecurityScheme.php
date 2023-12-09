<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class SecurityScheme implements JsonSerializable
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

    public function jsonSerialize(): array
    {
        $securityScheme = [
            'type' => $this->type,
        ];

        if ($this->description !== null) {
            $securityScheme['description'] = $this->description;
        }

        $securityScheme['name'] = $this->name;
        $securityScheme['in'] = $this->in;
        $securityScheme['scheme'] = $this->scheme;

        if ($this->bearerFormat !== null) {
            $securityScheme['bearerFormat'] = $this->bearerFormat;
        }

        return $securityScheme;
    }
}
