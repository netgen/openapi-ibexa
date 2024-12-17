<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema\ObjectSchema;

interface ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): ObjectSchema;
}
