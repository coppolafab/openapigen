<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi\Attributes;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Schema
{
    public function __construct(
        public ?string $name = null,
        public ?array $schema = null,
        public bool $additionalProperties = false,
        public array $examples = [],
    ) {
        if ($schema && ! $name) {
            throw new InvalidArgumentException('When schema is provided, name cannot be empty');
        }
    }
}
