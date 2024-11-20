<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema\ObjectSchema;

interface FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): ObjectSchema;
}
