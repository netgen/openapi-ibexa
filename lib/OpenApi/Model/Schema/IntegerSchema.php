<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class IntegerSchema extends Schema
{
    /**
     * @param int[]|null $enum
     */
    public function __construct(
        private ?array $enum = null,
        private ?int $const = null,
    ) {}

    public function getType(): string
    {
        return 'integer';
    }

    /**
     * @return int[]|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function getConst(): ?int
    {
        return $this->const;
    }
}
