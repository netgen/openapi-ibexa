<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi;

interface PathProviderInterface
{
    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Path>
     */
    public function providePaths(): iterable;
}
