<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;

final class TagsSchemaProvider implements SchemaProviderInterface
{
    public function provideSchemas(): iterable
    {
        yield from [
            'NetgenTags.Tag' => $this->buildTagSchema(),
        ];
    }

    private function buildTagSchema(): Schema\ObjectSchema
    {
        return new Schema\ObjectSchema(
            [
                'id' => new Schema\IntegerSchema(),
                'remoteId' => new Schema\StringSchema(),
                'parentTagId' => new Schema\IntegerSchema(),
                'keyword' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            ],
            null,
            ['id', 'remoteId', 'parentTagId', 'keyword'],
        );
    }
}
