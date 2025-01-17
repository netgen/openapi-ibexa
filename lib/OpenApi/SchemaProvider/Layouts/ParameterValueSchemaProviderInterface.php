<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

interface ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema;
}
