<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Contact implements JsonSerializable
{
    public function __construct(
        private ?string $name = null,
        private ?string $url = null,
        private ?string $email = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['name'] ?? null,
            $properties['url'] ?? null,
            $properties['email'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        $contact = [];

        if ($this->name !== null) {
            $contact['name'] = $this->name;
        }

        if ($this->url !== null) {
            $contact['url'] = $this->url;
        }

        if ($this->email !== null) {
            $contact['email'] = $this->email;
        }

        return $contact;
    }
}
