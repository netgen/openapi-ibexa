<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_keys;

final class MediaFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'fileName' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'fileSize' => new Schema\OneOfSchema(
                [
                    new Schema\IntegerSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'uri' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'inputUri' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'mimeType' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'width' => new Schema\IntegerSchema(),
            'height' => new Schema\IntegerSchema(),
            'autoplay' => new Schema\BooleanSchema(),
            'hasController' => new Schema\BooleanSchema(),
            'loop' => new Schema\BooleanSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
