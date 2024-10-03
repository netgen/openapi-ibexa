<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

final class StringSchema extends Schema
{
    /**
     * @param string[]|null $enum
     */
    public function __construct(
        private ?array $enum = null,
        private ?string $const = null,
        private ?Format $format = null,
    ) {}

    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string[]|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function getConst(): ?string
    {
        return $this->const;
    }

    public function getFormat(): ?Format
    {
        return $this->format;
    }
}
