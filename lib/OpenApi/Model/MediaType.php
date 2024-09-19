<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class MediaType
{
    public function __construct(
        private Schema $schema,
    ) {}

    public function getSchema(): Schema
    {
        return $this->schema;
    }
}
