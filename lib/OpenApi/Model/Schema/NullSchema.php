<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class NullSchema extends Schema
{
    public function getType(): string
    {
        return 'null';
    }
}
