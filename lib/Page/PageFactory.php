<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

use Netgen\Bundle\IbexaSiteApiBundle\QueryType\QueryDefinitionCollection;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\Layouts\API\Values\Layout\Layout;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PageFactory
{
    /**
     * @param iterable<\Netgen\OpenApiIbexa\Page\PagePartProviderInterface> $pagePartProviders
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private iterable $pagePartProviders,
    ) {}

    public function buildPage(?Content $content = null, ?QueryDefinitionCollection $queryDefinitionCollection = null, ?Layout $layout = null): Page
    {
        $queries = [];

        if ($content !== null && $content->mainLocation !== null) {
            foreach ($queryDefinitionCollection?->all() ?? [] as $identifier => $queryDefinition) {
                $queries[$identifier] = $this->urlGenerator->generate(
                    'netgen_openapi_ibexa_site_api_query',
                    [
                        'contentId' => $content->id,
                        'locationId' => $content->mainLocation->id,
                        'queryIdentifier' => $identifier,
                    ],
                );
            }
        }

        $page = new Page($content, $queries, $layout);

        foreach ($this->pagePartProviders as $pagePartProvider) {
            foreach ($pagePartProvider->providePageParts($page) as $identifier => $pagePart) {
                $page->addPagePart($identifier, $pagePart);
            }
        }

        return $page;
    }
}
