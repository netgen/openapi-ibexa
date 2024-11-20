<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi;

use Netgen\OpenApi\Model\Components;
use Netgen\OpenApi\Model\Info;
use Netgen\OpenApi\Model\OpenApi;
use Netgen\OpenApi\Model\Paths;
use Netgen\OpenApi\Model\Server;

final class OpenApiFactory
{
    /**
     * @param iterable<\Netgen\OpenApiIbexa\OpenApi\PathProviderInterface> $pathProviders
     * @param iterable<\Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface> $schemaProviders
     */
    public function __construct(
        private iterable $pathProviders,
        private iterable $schemaProviders,
        private string $routePrefix,
    ) {}

    public function buildModel(): OpenApi
    {
        $info = new Info();
        $servers = [new Server($this->routePrefix)];

        $paths = new Paths([...$this->getPaths()]);
        $components = new Components([...$this->getSchemas()]);

        return new OpenApi($info, $servers, $paths, $components);
    }

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Path>
     */
    private function getPaths(): iterable
    {
        foreach ($this->pathProviders as $pathProvider) {
            yield from $pathProvider->providePaths();
        }
    }

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Schema>
     */
    private function getSchemas(): iterable
    {
        foreach ($this->schemaProviders as $schemaProvider) {
            yield from $schemaProvider->provideSchemas();
        }
    }
}
