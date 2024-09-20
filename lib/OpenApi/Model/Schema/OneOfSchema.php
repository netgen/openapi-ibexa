<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use InvalidArgumentException;
use Netgen\IbexaOpenApi\OpenApi\Model\Discriminator;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function count;

final class OneOfSchema extends Schema
{
    /**
     * @param \Netgen\IbexaOpenApi\OpenApi\Model\Schema[] $oneOf
     */
    public function __construct(
        private array $oneOf,
        private ?Discriminator $discriminator = null,
    ) {
        if (count($this->oneOf) === 0) {
            throw new InvalidArgumentException('oneOf list must have at least one schema.');
        }
    }

    /**
     * @return \Netgen\IbexaOpenApi\OpenApi\Model\Schema[]
     */
    public function getOneOf(): array
    {
        return $this->oneOf;
    }

    public function getDiscriminator(): ?Discriminator
    {
        return $this->discriminator;
    }
}
