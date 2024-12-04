<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller\Page;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller as BaseController;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentView;
use Netgen\Layouts\Layout\Resolver\LayoutResolverInterface;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\PageFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class IbexaView extends BaseController
{
    public function __construct(
        private LayoutResolverInterface $layoutResolver,
        private PageFactory $pageFactory,
        private OutputVisitor $outputVisitor,
        private NormalizerInterface $normalizer,
    ) {}

    public function __invoke(ContentView $view): JsonResponse
    {
        $rule = $this->layoutResolver->resolveRule();

        $data = $this->normalizer->normalize(
            $this->outputVisitor->visit(
                $this->pageFactory->buildPage($view->getSiteContent(), $rule?->getLayout()),
            ),
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
