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
use Symfony\Component\Routing\RouterInterface;

final class ContentRelationsPathProvider implements PathProviderInterface
{
    public function __construct(
        private RouterInterface $router,
    ) {}

    public function providePaths(): iterable
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

        $routePath = $this->router->getRouteCollection()->get('netgen_openapi_ibexa_content_relations')?->getPath() ?? '';

        yield $routePath => new Path($getOperation);
    }
}
