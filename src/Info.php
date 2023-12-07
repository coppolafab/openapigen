<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;

final readonly class Info implements JsonSerializable
{
    public function __construct(
        private string $title,
        private string $version,
        private ?string $summary = null,
        private ?string $description = null,
        private ?string $termsOfService = null,
        private ?Contact $contact = null,
        private ?License $license = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $info = [
            'title' => $this->title,
        ];

        if ($this->summary !== null) {
            $info['summary'] = $this->summary;
        }

        if ($this->description !== null) {
            $info['description'] = $this->description;
        }

        if ($this->termsOfService !== null) {
            $info['termsOfService'] = $this->termsOfService;
        }

        if ($this->contact !== null) {
            $info['contact'] = $this->contact;
        }

        if ($this->license !== null) {
            $info['license'] = $this->license;
        }

        $info['version'] = $this->version;

        return $info;
    }
}
