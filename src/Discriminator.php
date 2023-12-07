<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

final readonly class Discriminator
{
    public function __construct(private string $propertyName, private ?array $mapping = null)
    {
    }
}
