<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use JsonSerializable;

final class Paths implements JsonSerializable
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path> $paths
     */
    public function __construct(
        private array $paths,
    ) {}

    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Path>
     */
    public function jsonSerialize(): iterable
    {
        yield from $this->paths;
    }
}
