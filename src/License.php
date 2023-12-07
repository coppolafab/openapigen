<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use InvalidArgumentException;
use JsonSerializable;

final readonly class License implements JsonSerializable
{
    public function __construct(
        private string $name,
        private ?string $identifier = null,
        private ?string $url = null,
    ) {
        if ($identifier !== null && $url !== null) {
            throw new InvalidArgumentException('The identifier and url fields are mutually exclusive');
        }
    }

    public function jsonSerialize(): array
    {
        $license = [
            'name' => $this->name,
        ];

        if ($this->identifier !== null) {
            $license['identifier'] = $this->identifier;
        } elseif ($this->url !== null) {
            $license['url'] = $this->url;
        }

        return $license;
    }
}
