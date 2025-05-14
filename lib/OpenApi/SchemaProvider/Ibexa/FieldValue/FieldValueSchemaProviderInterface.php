<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue;

use Netgen\OpenApi\Model\Schema;

interface FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema;
}
