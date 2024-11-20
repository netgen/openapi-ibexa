<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\PathProvider;

use Netgen\IbexaOpenApi\OpenApi\PathProviderInterface;
use Netgen\OpenApi\Model\MediaType;
use Netgen\OpenApi\Model\Operation;
use Netgen\OpenApi\Model\Parameter;
use Netgen\OpenApi\Model\ParameterIn;
use Netgen\OpenApi\Model\Path;
use Netgen\OpenApi\Model\Response;
use Netgen\OpenApi\Model\Responses;
use Netgen\OpenApi\Model\Schema\NumberSchema;
use Netgen\OpenApi\Model\Schema\ReferenceSchema;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class PagePathProvider implements PathProviderInterface
{
    public function providePaths(): iterable
    {
        $getOperation = new Operation(
            [new Parameter('id', ParameterIn::Path, new NumberSchema())],
            null,
            new Responses(
                [
                    SymfonyResponse::HTTP_OK => new Response(
                        'The page',
                        [],
                        [
                            'application/json' => new MediaType(new ReferenceSchema('Page')),
                        ],
                    ),
                ],
            ),
        );

        yield '/page/{id}' => new Path($getOperation);
    }
}
