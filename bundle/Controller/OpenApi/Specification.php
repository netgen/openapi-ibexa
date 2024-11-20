<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Controller\OpenApi;

use Netgen\OpenApiIbexa\OpenApi\OpenApiFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class Specification extends AbstractController
{
    public function __construct(
        private OpenApiFactory $openApiFactory,
        private NormalizerInterface $normalizer,
    ) {}

    public function __invoke(): JsonResponse
    {
        $data = $this->normalizer->normalize(
            $this->openApiFactory->buildModel(),
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
