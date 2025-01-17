<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

final class UrlParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\StringSchema(null, null, Schema\Format::Uri),
                new Schema\NullSchema(),
            ],
        );
    }
}
