<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi;

interface SchemaProviderInterface
{
    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Schema>
     */
    public function provideSchemas(): iterable;
}
