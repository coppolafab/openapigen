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
        private ?string $scheme = null,
        private ?OAuthFlows $flows = null,
        private ?string $openIdConnectUrl = null,
        private ?string $description = null,
        private ?string $bearerFormat = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['type'],
            $properties['name'],
            $properties['in'],
            $properties['scheme'] ?? null,
            $properties['flows'] ?? null,
            $properties['openIdConnectUrl'] ?? null,
            $properties['description'] ?? null,
            $properties['bearerFormat'] ?? null,
        );
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

        if ($this->scheme !== null) {
            $securityScheme['scheme'] = $this->scheme;
        }

        if ($this->bearerFormat !== null) {
            $securityScheme['bearerFormat'] = $this->bearerFormat;
        }

        return $securityScheme;
    }
}
