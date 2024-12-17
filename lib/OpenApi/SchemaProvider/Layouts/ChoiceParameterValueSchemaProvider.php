<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class ChoiceParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'value' => new Schema\OneOfSchema(
                [
                    new Schema\ArraySchema(new Schema\BooleanSchema()),
                    new Schema\ArraySchema(new Schema\IntegerSchema()),
                    new Schema\ArraySchema(new Schema\NumberSchema()),
                    new Schema\ArraySchema(new Schema\StringSchema()),
                    new Schema\BooleanSchema(),
                    new Schema\IntegerSchema(),
                    new Schema\NumberSchema(),
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
