<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page;

interface PagePartProviderInterface
{
    /**
     * @return iterable<string, object|scalar>
     */
    public function providePageParts(): iterable;
}
