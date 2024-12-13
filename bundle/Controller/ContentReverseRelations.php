<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\OpenApiIbexa\Page\ContentList;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use function json_encode;
use function max;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class ContentReverseRelations extends AbstractController
{
    public function __construct(
        private OutputVisitor $outputVisitor,
        private int $defaultLimit,
    ) {}

    public function __invoke(Content $content, string $fieldIdentifier, int $maxPerPage, int $currentPage): JsonResponse
    {
        $currentPage = max($currentPage, 1);

        if ($maxPerPage < 1) {
            $maxPerPage = $this->defaultLimit;
        }

        $relatedContent = $content->filterReverseFieldRelations($fieldIdentifier, [], $maxPerPage, $currentPage);

        $data = $this->outputVisitor->visit(new ContentList($relatedContent));

        return new JsonResponse(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
            Response::HTTP_OK,
            [],
            true,
        );
    }
}
