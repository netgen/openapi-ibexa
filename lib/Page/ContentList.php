<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

final class ContentList
{
    /**
     * @param iterable<\Netgen\IbexaSiteApi\API\Values\Content> $contentItems
     */
    public function __construct(
        private iterable $contentItems,
    ) {}

    /**
     * @return iterable<\Netgen\IbexaSiteApi\API\Values\Content>
     */
    public function getContentItems(): iterable
    {
        return $this->contentItems;
    }
}
