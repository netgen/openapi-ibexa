<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema\ObjectSchema;

interface FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): ObjectSchema;
}
