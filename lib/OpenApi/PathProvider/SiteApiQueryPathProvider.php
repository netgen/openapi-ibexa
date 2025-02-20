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

final class SiteApiQueryPathProvider implements PathProviderInterface
{
    public function __construct(
        private RouterInterface $router,
    ) {}

    public function providePaths(): iterable
    {
        $getOperation = new Operation(
            [
                new Parameter('contentId', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('locationId', ParameterIn::Path, new Schema\IntegerSchema()),
                new Parameter('queryIdentifier', ParameterIn::Path, new Schema\StringSchema()),
            ],
            null,
            new Responses(
                [
                    SymfonyResponse::HTTP_OK => new Response(
                        'Site API query',
                        [],
                        [
                            'application/json' => new MediaType(
                                new Schema\OneOfSchema(
                                    [
                                        new Schema\ReferenceSchema('SiteApi.ContentList'),
                                        new Schema\ReferenceSchema('SiteApi.LocationList'),
                                    ],
                                ),
                            ),
                        ],
                    ),
                ],
            ),
        );

        $routePath = $this->router->getRouteCollection()->get('netgen_openapi_ibexa_site_api_query')?->getPath() ?? '';

        yield $routePath => new Path($getOperation);
    }
}
