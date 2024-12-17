<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class IbexaObjectStateParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'value' => new Schema\OneOfSchema(
                [
                    new Schema\ArraySchema(new Schema\StringSchema()),
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
