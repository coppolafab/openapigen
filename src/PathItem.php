<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use JsonSerializable;
use Override;

final class PathItem implements JsonSerializable
{
    public function __construct(
        private readonly ?string $ref = null,
        private readonly ?string $summary = null,
        private readonly ?string $description = null,
        private ?Operation $get = null,
        private ?Operation $put = null,
        private ?Operation $post = null,
        private ?Operation $delete = null,
        private ?Operation $options = null,
        private ?Operation $head = null,
        private ?Operation $patch = null,
        private ?Operation $trace = null,
        private readonly ?array $servers = null,
        private readonly ?array $parameters = null,
    ) {
    }

    public static function __set_state(array $properties): self
    {
        return new self(
            $properties['ref'] ?? null,
            $properties['summary'] ?? null,
            $properties['description'] ?? null,
            $properties['get'] ?? null,
            $properties['put'] ?? null,
            $properties['post'] ?? null,
            $properties['delete'] ?? null,
            $properties['options'] ?? null,
            $properties['head'] ?? null,
            $properties['patch'] ?? null,
            $properties['trace'] ?? null,
            $properties['servers'] ?? null,
            $properties['parameters'] ?? null,
        );
    }

    public function getGet(): ?Operation
    {
        return $this->get;
    }

    public function setGet(?Operation $get): void
    {
        $this->get = $get;
    }

    public function getPut(): ?Operation
    {
        return $this->put;
    }

    public function setPut(?Operation $put): void
    {
        $this->put = $put;
    }

    public function getPost(): ?Operation
    {
        return $this->post;
    }

    public function setPost(?Operation $post): void
    {
        $this->post = $post;
    }

    public function getDelete(): ?Operation
    {
        return $this->delete;
    }

    public function setDelete(?Operation $delete): void
    {
        $this->delete = $delete;
    }

    public function getOptions(): ?Operation
    {
        return $this->options;
    }

    public function setOptions(?Operation $options): void
    {
        $this->options = $options;
    }

    public function getHead(): ?Operation
    {
        return $this->head;
    }

    public function setHead(?Operation $head): void
    {
        $this->head = $head;
    }

    public function getPatch(): ?Operation
    {
        return $this->patch;
    }

    public function setPatch(?Operation $patch): void
    {
        $this->patch = $patch;
    }

    public function getTrace(): ?Operation
    {
        return $this->trace;
    }

    public function setTrace(?Operation $trace): void
    {
        $this->trace = $trace;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $pathItem = [];

        if ($this->ref !== null) {
            $pathItem['ref'] = $this->ref;
        }

        if ($this->summary !== null) {
            $pathItem['summary'] = $this->summary;
        }

        if ($this->description !== null) {
            $pathItem['description'] = $this->description;
        }

        if ($this->get !== null) {
            $pathItem['get'] = $this->get;
        }

        if ($this->put !== null) {
            $pathItem['put'] = $this->put;
        }

        if ($this->post !== null) {
            $pathItem['post'] = $this->post;
        }

        if ($this->delete !== null) {
            $pathItem['delete'] = $this->delete;
        }

        if ($this->options !== null) {
            $pathItem['options'] = $this->options;
        }

        if ($this->head !== null) {
            $pathItem['head'] = $this->head;
        }

        if ($this->patch !== null) {
            $pathItem['patch'] = $this->patch;
        }

        if ($this->trace !== null) {
            $pathItem['trace'] = $this->trace;
        }

        return $pathItem;
    }
}
