<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class MetasFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'metas' => new Schema\ArraySchema(
                new Schema\ObjectSchema(
                    [
                        'name' => new Schema\StringSchema(),
                        'content' => new Schema\StringSchema(),
                        'fieldType' => new Schema\StringSchema(),
                        'required' => new Schema\OneOfSchema(
                            [
                                new Schema\BooleanSchema(),
                                new Schema\NullSchema(),
                            ],
                        ),
                        'minLength' => new Schema\OneOfSchema(
                            [
                                new Schema\StringSchema(),
                                new Schema\NullSchema(),
                            ],
                        ),
                        'maxLength' => new Schema\OneOfSchema(
                            [
                                new Schema\StringSchema(),
                                new Schema\NullSchema(),
                            ],
                        ),
                    ],
                    null,
                    ['name', 'content', 'fieldType', 'required', 'minLength', 'maxLength'],
                ),
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
