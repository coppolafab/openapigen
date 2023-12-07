<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

enum ParameterLocation: string
{
    case PATH = 'path';
    case QUERY = 'query';
    case HEADER = 'header';
    case COOKIE = 'cookie';
}
