<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Header
{
    public function __construct(
        private ?string $description,
        private bool $required = false,
        private bool $deprecated = false,
        private bool $allowEmptyValue = false,
        private ?string $style = null,
        private bool $explode = false,
        private bool $allowReserved = false,
        private ?Schema $schema = null,
        private mixed $example = null,
        private ?array $examples = null,
        private ?array $content = null,
    ) {
    }
}
