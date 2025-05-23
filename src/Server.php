<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class Server implements JsonSerializable
{
    public function __construct(
        private string $url,
        private ?string $description = null,
        private ?array $variables = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['url'],
            $properties['description'] ?? null,
            $properties['variables'] ?? null,
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $server = [
            'url' => $this->url,
        ];

        if ($this->description !== null) {
            $server['description'] = $this->description;
        }

        if ($this->variables !== null) {
            $server['variables'] = $this->variables;
        }

        return $server;
    }
}
