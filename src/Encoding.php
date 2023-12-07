<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Encoding
{
    public function __construct(
        private ?string $contentType = null,
        private ?array $headers = null,
        private ?StyleValue $style = null,
        private bool $explode = false,
        private bool $allowReserved = false,
    ) {
    }
}
