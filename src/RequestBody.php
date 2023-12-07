<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class RequestBody implements JsonSerializable
{
    public function __construct(
        private array $content,
        private ?string $description = null,
        private bool $required = false,
    ) {
    }

    public function jsonSerialize(): array
    {
        $requestBody = [];

        if ($this->description !== null) {
            $requestBody['description'] = $this->description;
        }

        $requestBody['content'] = $this->content;
        $requestBody['required'] = $this->required;

        return $requestBody;
    }
}
