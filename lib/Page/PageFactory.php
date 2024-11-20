<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\Layouts\API\Values\Layout\Layout;

final class PageFactory
{
    /**
     * @param iterable<\Netgen\OpenApiIbexa\Page\PagePartProviderInterface> $pagePartProviders
     */
    public function __construct(
        private iterable $pagePartProviders,
    ) {}

    public function buildPage(?Content $content = null, ?Layout $layout = null): Page
    {
        $page = new Page($content, $layout);

        foreach ($this->pagePartProviders as $pagePartProvider) {
            foreach ($pagePartProvider->providePageParts() as $identifier => $pagePart) {
                $page->addPagePart($identifier, $pagePart);
            }
        }

        return $page;
    }
}
