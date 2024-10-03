<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\PageSchemaPartProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ReferenceSchema;
use Netgen\IbexaOpenApi\OpenApi\PageSchemaPartProviderInterface;

final class SiteApiPageSchemaPartProvider implements PageSchemaPartProviderInterface
{
    public function providePageSchemaParts(): iterable
    {
        return [
            'content' => new ReferenceSchema('SiteApi.Content'),
            'location' => new ReferenceSchema('SiteApi.Location'),
        ];
    }

    public function getRequiredIdentifiers(): array
    {
        return [];
    }
}
