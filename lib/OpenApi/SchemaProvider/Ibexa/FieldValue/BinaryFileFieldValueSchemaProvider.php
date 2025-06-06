<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class BinaryFileFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        $properties = [
            'id' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'fileName' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'fileSize' => new Schema\OneOfSchema([new Schema\IntegerSchema(), new Schema\NullSchema()]),
            'uri' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'inputUri' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'mimeType' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'downloadCount' => new Schema\IntegerSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
