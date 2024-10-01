<?php

declare(strict_types=1);

namespace Netgen\Bundle\IbexaOpenApiBundle\Controller\Page;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\PageFactory;
use Netgen\IbexaSiteApi\API\LoadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class View extends AbstractController
{
    public function __construct(
        private LoadService $loadService,
        private PageFactory $pageFactory,
        private OutputVisitor $outputVisitor,
        private NormalizerInterface $normalizer,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $content = $this->loadService->loadContent($id);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = $this->normalizer->normalize(
            $this->outputVisitor->visit($this->pageFactory->buildPage($content)),
            'json',
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        return new JsonResponse(
            json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
            Response::HTTP_OK,
            [],
            true,
        );
    }
}
