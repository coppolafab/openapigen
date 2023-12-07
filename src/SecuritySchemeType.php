<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

enum SecuritySchemeType: string
{
    case APY_KEY = 'apiKey';
    case HTTP = 'http';
    case MUTUAL_TLS = 'mutualTLS';
    case OAUTH2 = 'oauth2';
    case OPENID_CONNECT = 'openIdConnect';
}
