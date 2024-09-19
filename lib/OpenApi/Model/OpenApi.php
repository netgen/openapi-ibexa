<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use function count;

final class OpenApi
{
    private const OPENAPI_VERSION = '3.1.0';

    private const JSON_SCHEMA_DIALECT = 'https://json-schema.org/draft-07/schema#';

    private string $openapi = self::OPENAPI_VERSION;

    private string $jsonSchemaDialect = self::JSON_SCHEMA_DIALECT;

    /**
     * @param \Netgen\IbexaOpenApi\OpenApi\Model\Server[] $servers
     */
    public function __construct(
        private Info $info,
        private array $servers,
        private Paths $paths,
        private Components $components,
    ) {}

    public function getOpenapi(): string
    {
        return $this->openapi;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function getJsonSchemaDialect(): string
    {
        return $this->jsonSchemaDialect;
    }

    /**
     * @return \Netgen\IbexaOpenApi\OpenApi\Model\Server[]
     */
    public function getServers(): array
    {
        if (count($this->servers) === 0) {
            return [new Server('/')];
        }

        return $this->servers;
    }

    public function getPaths(): Paths
    {
        return $this->paths;
    }

    public function getComponents(): Components
    {
        return $this->components;
    }
}
