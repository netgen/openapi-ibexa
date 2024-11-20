<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller\Page;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentView;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Layouts\Layout\Resolver\LayoutResolverInterface;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\PageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class View extends AbstractController
{
    public function __construct(
        private LoadService $loadService,
        private LayoutResolverInterface $layoutResolver,
        private PageFactory $pageFactory,
        private OutputVisitor $outputVisitor,
        private NormalizerInterface $normalizer,
    ) {}

    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $content = $this->loadService->loadContent($id);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $view = new ContentView();
        $view->setSiteContent($content);

        if ($content->mainLocation !== null) {
            $view->setSiteLocation($content->mainLocation);
        }

        $request->attributes->set('view', $view);

        $rule = $this->layoutResolver->resolveRule();

        $data = $this->normalizer->normalize(
            $this->outputVisitor->visit($this->pageFactory->buildPage($content, $rule?->getLayout())),
            'json',
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        return new JsonResponse(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
            Response::HTTP_OK,
            [],
            true,
        );
    }
}
