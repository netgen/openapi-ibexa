<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

final class IbexaLocationParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\ReferenceSchema('SiteApi.Location'),
                new Schema\NullSchema(),
            ],
        );
    }
}
