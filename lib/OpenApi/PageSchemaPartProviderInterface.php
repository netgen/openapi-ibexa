<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi;

interface PageSchemaPartProviderInterface
{
    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>
     */
    public function providePageSchemaParts(): iterable;

    /**
     * @return iterable<string>
     */
    public function getRequiredIdentifiers(): iterable;
}
