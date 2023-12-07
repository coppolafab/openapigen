<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

enum OperationVerb
{
    case GET;
    case PUT;
    case POST;
    case DELETE;
    case OPTIONS;
    case HEAD;
    case PATCH;
    case TRACE;
}
