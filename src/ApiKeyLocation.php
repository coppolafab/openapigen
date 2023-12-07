<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

enum ApiKeyLocation: string
{
    case QUERY = 'query';
    case HEADER = 'header';
    case COOKIE = 'cookie';
}
