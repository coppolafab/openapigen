<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Response implements JsonSerializable
{
    public function __construct(
        private string $description,
        private ?array $headers = null,
        private ?array $content = null,
        private ?array $links = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['description'],
            $properties['headers'] ?? null,
            $properties['content'] ?? null,
            $properties['links'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        $response = [
            'description' => $this->description,
        ];

        if ($this->headers !== null) {
            $response['headers'] = $this->headers;
        }

        if ($this->content !== null) {
            $response['content'] = $this->content;
        }

        if ($this->links !== null) {
            $response['links'] = $this->links;
        }

        return $response;
    }
}
