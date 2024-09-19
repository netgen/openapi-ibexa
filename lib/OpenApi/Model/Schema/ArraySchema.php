<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class ArraySchema extends Schema
{
    public function __construct(
        private Schema $items,
    ) {}

    public function getItems(): Schema
    {
        return $this->items;
    }
}
