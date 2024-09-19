<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class BooleanSchema extends Schema
{
    public function __construct(
        private ?bool $const = null,
    ) {}

    public function getType(): string
    {
        return 'boolean';
    }

    public function getConst(): ?bool
    {
        return $this->const;
    }
}
