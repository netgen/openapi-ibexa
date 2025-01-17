<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema;

final class DateFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\StringSchema(null, null, Schema\Format::Date),
                new Schema\NullSchema(),
            ],
        );
    }
}
