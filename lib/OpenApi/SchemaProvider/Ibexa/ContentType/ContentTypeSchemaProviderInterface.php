<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\ContentType;

interface ContentTypeSchemaProviderInterface
{
    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Schema>
     */
    public function provideContentTypeSchemas(): iterable;
}
