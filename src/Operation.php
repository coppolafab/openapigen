<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Operation implements JsonSerializable
{
    public function __construct(
        private ?array $tags = null,
        private ?string $summary = null,
        private ?string $description = null,
        private ?ExternalDocumentation $externalDocs = null,
        private ?string $operationId = null,
        private ?array $parameters = null,
        private RequestBody|Reference|null $requestBody = null,
        private ?Responses $responses = null,
        private ?array $callbacks = null,
        private bool $deprecated = false,
        private ?array $security = null,
        private ?array $servers = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['tags'] ?? null,
            $properties['summary'] ?? null,
            $properties['description'] ?? null,
            $properties['externalDocs'] ?? null,
            $properties['operationId'] ?? null,
            $properties['parameters'] ?? null,
            $properties['requestBody'] ?? null,
            $properties['responses'] ?? null,
            $properties['callbacks'] ?? null,
            $properties['deprecated'] ?? false,
            $properties['security'] ?? null,
            $properties['servers'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        $operation = [];

        if ($this->tags !== null) {
            $operation['tags'] = $this->tags;
        }

        if ($this->summary !== null) {
            $operation['summary'] = $this->summary;
        }

        if ($this->description !== null) {
            $operation['description'] = $this->description;
        }

        if ($this->externalDocs !== null) {
            $operation['externalDocs'] = $this->externalDocs;
        }

        if ($this->operationId !== null) {
            $operation['operationId'] = $this->operationId;
        }

        if ($this->parameters !== null) {
            $operation['parameters'] = $this->parameters;
        }

        if ($this->requestBody !== null) {
            $operation['requestBody'] = $this->requestBody;
        }

        if ($this->responses !== null) {
            $operation['responses'] = $this->responses;
        }

        if ($this->callbacks !== null) {
            $operation['callbacks'] = $this->callbacks;
        }

        if ($this->deprecated !== null) {
            $operation['deprecated'] = $this->deprecated;
        }

        if ($this->security !== null) {
            $operation['security'] = $this->security;
        }

        if ($this->servers !== null) {
            $operation['servers'] = $this->servers;
        }

        return $operation;
    }
}
