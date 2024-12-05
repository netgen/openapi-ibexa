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
}
