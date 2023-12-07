<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

enum StyleValue: string
{
    case MATRIX = 'matrix';
    case LABEL = 'label';
    case FORM = 'form';
    case SIMPLE = 'simple';
    case SPACE_DELIMITED = 'spaceDelimited';
    case PIPE_DELIMITED = 'pipeDelimited';
    case DEEP_OBJECT = 'deepObject';
}
