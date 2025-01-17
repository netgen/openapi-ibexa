<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

final class ChoiceParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        return new Schema\OneOfSchema(
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
        );
    }
}
