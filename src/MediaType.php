<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use InvalidArgumentException;
use JsonSerializable;
use Override;

final readonly class MediaType implements JsonSerializable
{
    public const APPLICATION_JSON = 'application/json';

    public function __construct(
        private mixed $schema = null,
        private ?Example $example = null,
        private ?array $examples = null,
        private ?array $encoding = null,
    ) {
        if ($example !== null && $examples !== null) {
            throw new InvalidArgumentException('The example and examples fields are mutually exclusive');
        }
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['schema'] ?? null,
            $properties['example'] ?? null,
            $properties['examples'] ?? null,
            $properties['encoding'] ?? null,
        );
    }

    public function getSchema(): mixed
    {
        return $this->schema;
    }

    public function getExamples(): ?array
    {
        return $this->examples;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $mediaType = [];

        if ($this->schema !== null) {
            $mediaType['schema'] = $this->schema;
        }

        if ($this->example !== null) {
            // seems Swagger editor do not render complete example object
            $mediaType['example'] = $this->example->value;
        }

        if ($this->examples !== null) {
            $mediaType['examples'] = $this->examples;
        }

        if ($this->encoding !== null) {
            $mediaType['encoding'] = $this->encoding;
        }

        return $mediaType;
    }
}
