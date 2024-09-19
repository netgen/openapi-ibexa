<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi;

interface SchemaProviderInterface
{
    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>
     */
    public function provideSchemas(): iterable;
}
