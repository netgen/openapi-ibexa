<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\PageSchemaPartProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ReferenceSchema;
use Netgen\IbexaOpenApi\OpenApi\PageSchemaPartProviderInterface;

final class LayoutsPageSchemaPartProvider implements PageSchemaPartProviderInterface
{
    public function providePageSchemaParts(): iterable
    {
        yield 'layout' => new ReferenceSchema('Layouts.Layout');
    }

    public function getRequiredIdentifiers(): array
    {
        return [];
    }
}
