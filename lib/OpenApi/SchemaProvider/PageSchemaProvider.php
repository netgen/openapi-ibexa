<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema\StringSchema;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;

final class PageSchemaProvider implements SchemaProviderInterface
{
    public function provideSchemas(): iterable
    {
        yield 'Default.Page' => new StringSchema();
    }
}
