<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

enum ParameterIn: string
{
    case Query = 'query';
    case Header = 'header';
    case Path = 'path';
    case Cookie = 'cookie';
}
