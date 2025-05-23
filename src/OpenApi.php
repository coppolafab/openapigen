<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class OpenApi implements JsonSerializable
{
    public function __construct(
        private string $openapi,
        private Info $info,
        private ?string $jsonSchemaDialect = null,
        private ?array $servers = null,
        private ?Paths $paths = null,
        private ?array $webhooks = null,
        private ?Components $components = null,
        private ?array $security = null,
        private ?array $tags = null,
        private ?ExternalDocumentation $externalDocs = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['openapi'],
            $properties['info'],
            $properties['jsonSchemaDialect'] ?? null,
            $properties['servers'] ?? null,
            $properties['paths'] ?? null,
            $properties['webhooks'] ?? null,
            $properties['components'] ?? null,
            $properties['security'] ?? null,
            $properties['tags'] ?? null,
            $properties['externalDocs'] ?? null,
        );
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function getServers(): ?array
    {
        return $this->servers;
    }

    public function getPaths(): ?Paths
    {
        return $this->paths;
    }

    public function getComponents(): ?Components
    {
        return $this->components;
    }

    public function getSecurity(): ?array
    {
        return $this->security;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $openApi = [
            'openapi' => $this->openapi,
            'info' => $this->info,
        ];

        if ($this->jsonSchemaDialect !== null) {
            $openApi['jsonSchemaDialect'] = $this->jsonSchemaDialect;
        }

        if ($this->servers !== null) {
            $openApi['servers'] = $this->servers;
        }

        if ($this->paths !== null) {
            $openApi['paths'] = $this->paths;
        }

        if ($this->components !== null) {
            $openApi['components'] = $this->components;
        }

        return $openApi;
    }
}
