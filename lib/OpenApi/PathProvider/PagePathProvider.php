<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\PathProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\MediaType;
use Netgen\IbexaOpenApi\OpenApi\Model\Operation;
use Netgen\IbexaOpenApi\OpenApi\Model\Parameter;
use Netgen\IbexaOpenApi\OpenApi\Model\ParameterIn;
use Netgen\IbexaOpenApi\OpenApi\Model\Path;
use Netgen\IbexaOpenApi\OpenApi\Model\Response;
use Netgen\IbexaOpenApi\OpenApi\Model\Responses;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema\NumberSchema;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ReferenceSchema;
use Netgen\IbexaOpenApi\OpenApi\PathProviderInterface;
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
                            'application/json' => new MediaType(new ReferenceSchema('Default.Page')),
                        ],
                    ),
                ],
            ),
        );

        yield '/page/{id}' => new Path($getOperation);
    }
}
