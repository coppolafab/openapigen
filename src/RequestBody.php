<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final readonly class RequestBody implements JsonSerializable
{
    public function __construct(
        private array $content,
        private ?string $description = null,
        private bool $required = false,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['content'],
            $properties['description'] ?? null,
            $properties['required'] ?? false,
        );
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    #[Override]
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
