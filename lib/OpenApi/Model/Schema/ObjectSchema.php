<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_unique;
use function array_values;

final class ObjectSchema extends Schema
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema> $properties
     * @param string[] $required
     */
    public function __construct(
        private array $properties,
        private array $required = [],
    ) {}

    public function getType(): string
    {
        return 'object';
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return string[]
     */
    public function getRequired(): array
    {
        return array_values(array_unique($this->required));
    }
}
