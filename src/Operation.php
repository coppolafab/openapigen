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

        if ($this->requestBody !== null) {
            $operation['requestBody'] = $this->requestBody;
        }

        if ($this->deprecated !== null) {
            $operation['deprecated'] = $this->deprecated;
        }

        if ($this->responses !== null) {
            $operation['responses'] = $this->responses;
        }

        return $operation;
    }
}
