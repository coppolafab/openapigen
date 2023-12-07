<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Server implements JsonSerializable
{
    public function __construct(
        private string $url,
        private ?string $description = null,
        private ?array $variables = null,
    ) {
    }

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
