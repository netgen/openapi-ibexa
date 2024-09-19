<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi;

use Netgen\IbexaOpenApi\OpenApi\Model\Components;
use Netgen\IbexaOpenApi\OpenApi\Model\Info;
use Netgen\IbexaOpenApi\OpenApi\Model\OpenApi;
use Netgen\IbexaOpenApi\OpenApi\Model\Paths;
use Netgen\IbexaOpenApi\OpenApi\Model\Server;

final class OpenApiFactory
{
    /**
     * @param iterable<\Netgen\IbexaOpenApi\OpenApi\PathProviderInterface> $pathProviders
     * @param iterable<\Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface> $schemaProviders
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
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path>
     */
    private function getPaths(): iterable
    {
        foreach ($this->pathProviders as $pathProvider) {
            yield from $pathProvider->providePaths();
        }
    }

    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>
     */
    private function getSchemas(): iterable
    {
        foreach ($this->schemaProviders as $schemaProvider) {
            yield from $schemaProvider->provideSchemas();
        }
    }
}
