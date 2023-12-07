<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class SecurityRequirement
{
    public function __construct(private array $schemes = [])
    {
    }
}
