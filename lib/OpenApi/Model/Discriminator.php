<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use Netgen\IbexaOpenApi\OpenApi\Model\Util\Reference;

use function array_map;

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
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Util\Reference>|null
     */
    public function getMapping(): ?array
    {
        if ($this->mapping === null) {
            return null;
        }

        return array_map(
            static fn (string $schemaName): Reference => new Reference($schemaName),
            $this->mapping,
        );
    }
}
