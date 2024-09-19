<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class NumberSchema extends Schema
{
    /**
     * @param array<int|float>|null $enum
     */
    public function __construct(
        private ?array $enum = null,
        private int|float|null $const = null,
    ) {}

    public function getType(): string
    {
        return 'number';
    }

    /**
     * @return array<int|float>|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function getConst(): int|float|null
    {
        return $this->const;
    }
}
