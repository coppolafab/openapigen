<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

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
