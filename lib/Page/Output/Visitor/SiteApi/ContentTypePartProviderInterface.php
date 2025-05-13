<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi;

use Netgen\IbexaSiteApi\API\Values\Content;

interface ContentTypePartProviderInterface
{
    /**
     * @return iterable<string, mixed>
     */
    public function provideContentTypeParts(Content $content): iterable;
}
