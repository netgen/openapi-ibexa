<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class RemoteMediaFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        $properties = [
            'remoteId' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'type' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'url' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'md5' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'id' => new Schema\OneOfSchema(
                [
                    new Schema\IntegerSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'name' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'originalFilename' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'version' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'visibility' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'folder' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'size' => new Schema\OneOfSchema(
                [
                    new Schema\IntegerSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'altText' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'caption' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
            'tags' => new Schema\ArraySchema(new Schema\StringSchema()),
            'metadata' => new Schema\ObjectSchema(),
            'context' => new Schema\ObjectSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
