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

    public function jsonSerialize(): array
    {
        $components = [];

        if ($this->responses !== null) {
            $components['responses'] = $this->responses;
        }

        if ($this->securitySchemes !== null) {
            $components['securitySchemes'] = $this->securitySchemes;
        }

        return $components;
    }
}
