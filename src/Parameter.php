<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Parameter
{
    public function __construct(
        private string $name,
        private ?ParameterLocation $in,
        private ?string $description,
        private bool $required = false,
        private bool $deprecated = false,
        private bool $allowEmptyValue = false,
        private ?string $style = null,
        private bool $explode = false,
        private bool $allowReserved = false,
        private mixed $schema = null,
        private $example = null,
        private ?array $examples = null,
        private ?array $content = null,
    ) {
    }
}
