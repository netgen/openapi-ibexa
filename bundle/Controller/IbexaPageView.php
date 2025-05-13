<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller as BaseController;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentView;
use Netgen\Layouts\Layout\Resolver\LayoutResolverInterface;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\PageFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class IbexaPageView extends BaseController
{
    public function __construct(
        private LayoutResolverInterface $layoutResolver,
        private PageFactory $pageFactory,
        private OutputVisitor $outputVisitor,
    ) {}

    public function __invoke(ContentView $view): JsonResponse
    {
        /** @var \Netgen\Bundle\IbexaSiteApiBundle\QueryType\QueryDefinitionCollection $queryDefinitionCollection */
        $queryDefinitionCollection = $view->getParameter(ContentView::QUERY_DEFINITION_COLLECTION_NAME);

        $rule = $this->layoutResolver->resolveRule();

        $data = $this->outputVisitor->visit(
            $this->pageFactory->buildPage($view->getSiteContent(), $view->getSiteLocation(), $queryDefinitionCollection, $rule?->getLayout()),
        );

        return new JsonResponse(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
            Response::HTTP_OK,
            [],
            true,
        );
    }
}
