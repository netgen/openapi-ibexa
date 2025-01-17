<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

final class IbexaContentTypeParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\ArraySchema(new Schema\StringSchema()),
                new Schema\StringSchema(),
                new Schema\NullSchema(),
            ],
        );
    }
}
