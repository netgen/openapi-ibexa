<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class EnhancedLinkFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        $properties = [
            'type' => new Schema\StringSchema(),
            'target' => new Schema\StringSchema(),
            'label' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'url' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'path' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'suffix' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
