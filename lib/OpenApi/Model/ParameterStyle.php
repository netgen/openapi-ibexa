<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

enum ParameterStyle: string
{
    case Matrix = 'matrix';

    case Label = 'label';

    case Form = 'form';

    case Simple = 'simple';

    case SpaceDelimited = 'spaceDelimited';

    case PipeDelimited = 'pipeDelimited';

    case deepObject = 'deepObject';
}
