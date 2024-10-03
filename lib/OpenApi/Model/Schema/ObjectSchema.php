<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use InvalidArgumentException;
use Netgen\IbexaOpenApi\OpenApi\Model\Discriminator;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_unique;
use function count;

final class ObjectSchema extends Schema
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>|null $properties
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>|null $patternProperties
     * @param string[]|null $required
     */
    public function __construct(
        private ?array $properties = null,
        private ?array $patternProperties = null,
        private ?array $required = null,
        private ?Discriminator $discriminator = null,
    ) {
        if ($this->required !== null && count($this->required) !== count(array_unique($this->required))) {
            throw new InvalidArgumentException('List of required properties must unique.');
        }
    }

    public function getType(): string
    {
        return 'object';
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema>|null
     */
    public function getPatternProperties(): ?array
    {
        return $this->patternProperties;
    }

    /**
     * @return string[]|null
     */
    public function getRequired(): ?array
    {
        return $this->required;
    }

    public function getDiscriminator(): ?Discriminator
    {
        return $this->discriminator;
    }
}
