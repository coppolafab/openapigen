<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Components implements JsonSerializable
{
    public function __construct(
        private ?array $schemas = null,
        private ?array $responses = null,
        private ?array $parameters = null,
        private ?array $examples = null,
        private ?array $requestBodies = null,
        private ?array $headers = null,
        private ?array $securitySchemes = null,
        private ?array $links = null,
        private ?array $callbacks = null,
        private ?array $pathItems = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['schemas'] ?? null,
            $properties['responses'] ?? null,
            $properties['parameters'] ?? null,
            $properties['examples'] ?? null,
            $properties['requestBodies'] ?? null,
            $properties['headers'] ?? null,
            $properties['securitySchemes'] ?? null,
            $properties['links'] ?? null,
            $properties['callbacks'] ?? null,
            $properties['pathItems'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        $components = [];

        if ($this->schemas !== null) {
            $components['schemas'] = $this->schemas;
        }

        if ($this->responses !== null) {
            $components['responses'] = $this->responses;
        }

        if ($this->parameters !== null) {
            $components['parameters'] = $this->parameters;
        }

        if ($this->examples !== null) {
            $components['examples'] = $this->examples;
        }

        if ($this->requestBodies !== null) {
            $components['requestBodies'] = $this->requestBodies;
        }

        if ($this->headers !== null) {
            $components['headers'] = $this->headers;
        }

        if ($this->securitySchemes !== null) {
            $components['securitySchemes'] = $this->securitySchemes;
        }

        if ($this->links !== null) {
            $components['links'] = $this->links;
        }

        if ($this->callbacks !== null) {
            $components['callbacks'] = $this->callbacks;
        }

        if ($this->pathItems !== null) {
            $components['pathItems'] = $this->pathItems;
        }

        return $components;
    }
}
