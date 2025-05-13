<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

use Netgen\Bundle\IbexaSiteApiBundle\QueryType\QueryDefinitionCollection;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
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

    public function buildPage(
        ?Content $content = null,
        ?Location $location = null,
        ?QueryDefinitionCollection $queryDefinitionCollection = null,
        ?Layout $layout = null,
    ): Page {
        $queries = [];

        if ($content !== null && ($location ?? $content->mainLocation) !== null) {
            foreach ($queryDefinitionCollection?->all() ?? [] as $identifier => $queryDefinition) {
                $queries[$identifier] = $this->urlGenerator->generate(
                    'netgen_openapi_ibexa_site_api_query',
                    [
                        'contentId' => $content->id,
                        'locationId' => ($location ?? $content->mainLocation)->id,
                        'queryIdentifier' => $identifier,
                    ],
                );
            }
        }

        $page = new Page($content, $location, $queries, $layout);

        foreach ($this->pagePartProviders as $pagePartProvider) {
            foreach ($pagePartProvider->providePageParts($page) as $identifier => $pagePart) {
                $page->addPagePart($identifier, $pagePart);
            }
        }

        return $page;
    }
}
