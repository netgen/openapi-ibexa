<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

class Discriminator
{
    /**
     * @param array<string, string>|null $mapping
     */
    public function __construct(
        private string $propertyName,
        private ?array $mapping = null,
    ) {}

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return array<string, string>|null
     */
    public function getMapping(): ?array
    {
        return $this->mapping;
    }
}
