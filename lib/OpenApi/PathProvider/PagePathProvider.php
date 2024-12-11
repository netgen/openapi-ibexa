<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\PathProvider;

use Netgen\OpenApi\Model\MediaType;
use Netgen\OpenApi\Model\Operation;
use Netgen\OpenApi\Model\Parameter;
use Netgen\OpenApi\Model\ParameterIn;
use Netgen\OpenApi\Model\Path;
use Netgen\OpenApi\Model\Response;
use Netgen\OpenApi\Model\Responses;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\PathProviderInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function sprintf;
use function trim;

final class PagePathProvider implements PathProviderInterface
{
    public function __construct(
        private string $pathPrefix,
        private bool $useIbexaFullView = false,
    ) {}

    public function providePaths(): iterable
    {
        yield from $this->buildPageOperation();

        yield from $this->buildLocationChildrenOperation();

        yield from $this->buildContentRelationsOperation();
    }

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Path>
     */
    private function buildPageOperation(): iterable
    {
        $getOperation = new Operation(
            [new Parameter('path', ParameterIn::Path, new Schema\StringSchema())],
            null,
            new Responses(
                [
                    SymfonyResponse::HTTP_OK => new Response(
                        'The page',
                        [],
                        [
                            'application/json' => new MediaType(new Schema\ReferenceSchema('Page')),
                        ],
                    ),
                ],
            ),
        );

        $pagePath = sprintf('/%s/{path}', trim($this->pathPrefix, '/'));

        if ($this->useIbexaFullView) {
            $pagePath = '/{path}';
        }

        yield $pagePath => new Path($getOperation);
    }

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Path>
     */
    private function buildLocationChildrenOperation(): iterable
    {
        $getOperation = new Operation(
            [
                new Parameter('locationId', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('maxPerPage', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('currentPage', ParameterIn::Path, new Schema\IntegerSchema()),
            ],
            null,
            new Responses(
                [
                    SymfonyResponse::HTTP_OK => new Response(
                        'Location children',
                        [],
                        [
                            'application/json' => new MediaType(new Schema\ReferenceSchema('SiteApi.LocationList')),
                        ],
                    ),
                ],
            ),
        );

        $pagePath = sprintf('/%s/location/{locationId}/children/{maxPerPage}/{currentPage}', trim($this->pathPrefix, '/'));

        yield $pagePath => new Path($getOperation);
    }

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Path>
     */
    private function buildContentRelationsOperation(): iterable
    {
        $getOperation = new Operation(
            [
                new Parameter('contentId', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('fieldIdentifier', ParameterIn::Path, new Schema\StringSchema()),
                new Parameter('maxPerPage', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('currentPage', ParameterIn::Path, new Schema\IntegerSchema()),
            ],
            null,
            new Responses(
                [
                    SymfonyResponse::HTTP_OK => new Response(
                        'Content relations',
                        [],
                        [
                            'application/json' => new MediaType(new Schema\ReferenceSchema('SiteApi.ContentList')),
                        ],
                    ),
                ],
            ),
        );

        $pagePath = sprintf('/%s/content/{contentId}/relations/{fieldIdentifier}/{maxPerPage}/{currentPage}', trim($this->pathPrefix, '/'));

        yield $pagePath => new Path($getOperation);
    }
}
