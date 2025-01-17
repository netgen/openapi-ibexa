<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class DateTimeParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        $properties = [
            'datetime' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(null, null, Schema\Format::DateTime),
                    new Schema\NullSchema(),
                ],
            ),
            'timezone' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
