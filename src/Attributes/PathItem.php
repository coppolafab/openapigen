<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class PathItem
{
    public function __construct(
        public string $path,
        public ?string $ref = null,
        public ?string $summary = null,
        public ?string $description = null,
    ) {
    }
}
