<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue;

use Netgen\OpenApi\Model\Schema;

final class FloatFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        return new Schema\OneOfSchema([new Schema\NumberSchema(), new Schema\NullSchema()]);
    }
}
