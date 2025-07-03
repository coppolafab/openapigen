<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi\Attributes\Schema;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Property
{
    public function __construct(
        public ?string $format = null,
    ) {
    }
}
