<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue;

use Netgen\OpenApi\Model\Schema;

final class MatrixFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        return new Schema\ArraySchema(
            new Schema\ObjectSchema(
                null,
                null,
                null,
                null,
                new Schema\StringSchema(),
            ),
        );
    }
}
