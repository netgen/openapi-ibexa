<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller;

use Ibexa\Contracts\Core\Repository\Values\Content\LocationQuery;
use Ibexa\Core\QueryType\QueryTypeRegistry;
use Netgen\Bundle\IbexaSiteApiBundle\QueryType\QueryExecutor;
use Netgen\Bundle\IbexaSiteApiBundle\View\Builder\ContentViewBuilder;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentView;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\OpenApiIbexa\Page\ContentList;
use Netgen\OpenApiIbexa\Page\LocationList;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use OutOfBoundsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class SiteApiQuery extends AbstractController
{
    public function __construct(
        private ContentViewBuilder $viewBuilder,
        private QueryTypeRegistry $queryTypeRegistry,
        private QueryExecutor $queryExecutor,
        private OutputVisitor $outputVisitor,
    ) {}

    public function __invoke(Content $content, Location $location, string $queryIdentifier): JsonResponse
    {
        $viewParameters = [
            'content' => $content,
            'location' => $location,
            'viewType' => 'full',
            'layout' => false,
            '_controller' => 'ng_content::viewAction',
        ];

        $view = $this->viewBuilder->buildView($viewParameters);

        /** @var \Netgen\Bundle\IbexaSiteApiBundle\QueryType\QueryDefinitionCollection $queryDefinitionCollection */
        $queryDefinitionCollection = $view->getParameter(ContentView::QUERY_DEFINITION_COLLECTION_NAME);

        try {
            $queryDefinition = $queryDefinitionCollection->get($queryIdentifier);
        } catch (OutOfBoundsException) {
            throw new NotFoundHttpException();
        }

        $queryResult = $this->queryExecutor->execute($queryDefinition);

        $query = $this->queryTypeRegistry
            ->getQueryType($queryDefinition->name)
            ->getQuery($queryDefinition->parameters);

        $queryResult = $query instanceof LocationQuery ?
            new LocationList($queryResult) :
            new ContentList($queryResult);

        $data = $this->outputVisitor->visit($queryResult);

        return new JsonResponse(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
            Response::HTTP_OK,
            [],
            true,
        );
    }
}
