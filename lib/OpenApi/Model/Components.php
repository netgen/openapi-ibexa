<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Components
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema> $schemas
     */
    public function __construct(
        private array $schemas,
    ) {}

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>
     */
    public function getSchemas(): array
    {
        return $this->schemas;
    }
}
