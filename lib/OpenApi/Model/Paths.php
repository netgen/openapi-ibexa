<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use InvalidArgumentException;
use JsonSerializable;

use function count;

final class Paths implements JsonSerializable
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path> $paths
     */
    public function __construct(
        private array $paths,
    ) {
        if (count($this->paths) === 0) {
            throw new InvalidArgumentException('OpenAPI specification requires at least one path.');
        }
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path>
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path>
     */
    public function jsonSerialize(): iterable
    {
        yield from $this->paths;
    }
}
