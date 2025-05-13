<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Attribute;

use Attribute;

/**
 * Service tag to autoconfigure content type schema providers.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsContentTypeSchemaProvider
{
    public string $contentType;

    public function __construct(string $contentType)
    {
        $this->contentType = $contentType;
    }
}
