<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema;

final class TagsFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema
    {
        return new Schema\ArraySchema(
            new Schema\ObjectSchema(
                [
                    'id' => new Schema\IntegerSchema(),
                    'remoteId' => new Schema\StringSchema(),
                    'parentTagId' => new Schema\IntegerSchema(),
                    'keyword' => new Schema\OneOfSchema(
                        [
                            new Schema\StringSchema(),
                            new Schema\NullSchema(),
                        ],
                    ),
                ],
                null,
                ['id', 'remoteId', 'parentTagId', 'keyword'],
            ),
        );
    }
}
