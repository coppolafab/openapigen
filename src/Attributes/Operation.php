<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi\Attributes;

use Attribute;
use Coppolafab\OpenApi\OperationVerb;
use Coppolafab\OpenApi\Reference;
use Coppolafab\OpenApi\RequestBody;
use Coppolafab\OpenApi\Responses;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
readonly class Operation
{
    public function __construct(
        public OperationVerb $verb,
        public ?array $tags = null,
        public ?string $summary = null,
        public ?string $description = null,
        public ?string $operationId = null,
        public RequestBody|Reference|null $requestBody = null,
        public ?Responses $responses = null,
        public bool $deprecated = false,
    ) {
    }
}
