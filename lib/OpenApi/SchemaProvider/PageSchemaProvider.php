<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ObjectSchema;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;

final class PageSchemaProvider implements SchemaProviderInterface
{
    /**
     * @param iterable<\Netgen\IbexaOpenApi\OpenApi\PageSchemaPartProviderInterface> $pageSchemaPartProviders
     */
    public function __construct(
        private iterable $pageSchemaPartProviders,
    ) {}

    public function provideSchemas(): iterable
    {
        $properties = [];
        $required = [];

        foreach ($this->pageSchemaPartProviders as $pageSchemaPartProvider) {
            $properties += [...$pageSchemaPartProvider->providePageSchemaParts()];
            // Cannot use += here due to numeric keys
            $required = [...$required, ...$pageSchemaPartProvider->getRequiredIdentifiers()];
        }

        yield 'Default.Page' => new ObjectSchema($properties, null, $required);
    }
}
