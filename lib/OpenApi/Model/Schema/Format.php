<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

enum Format: string
{
    case DateTime = 'date-time';
    case Date = 'date';
    case Time = 'time';
    case Duration = 'duration';
    case Email = 'email';
    case IdnEmail = 'idn-email';
    case HostName = 'hostname';
    case IdnHostName = 'idn-hostname';
    case IPv4 = 'ipv4';
    case IPv6 = 'ipv6';
    case Uri = 'uri';
    case UriReference = 'uri-reference';
    case Iri = 'iri';
    case IriReference = 'iri-reference';
    case Uuid = 'uuid';
    case UriTemplate = 'uri-template';
    case JsonPointer = 'json-pointer';
    case RelativeJsonPointer = 'relative-json-pointer';
    case Regex = 'regex';
}
