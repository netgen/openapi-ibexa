<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use InvalidArgumentException;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function count;

final class AllOfSchema extends Schema
{
    /**
     * @param \Netgen\IbexaOpenApi\OpenApi\Model\Schema[] $allOf
     */
    public function __construct(
        private array $allOf,
    ) {
        if (count($this->allOf) === 0) {
            throw new InvalidArgumentException('allOf list must have at least one schema.');
        }
    }

    /**
     * @return \Netgen\IbexaOpenApi\OpenApi\Model\Schema[]
     */
    public function getAllOf(): array
    {
        return $this->allOf;
    }
}
