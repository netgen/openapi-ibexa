<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use InvalidArgumentException;
use JsonSerializable;

use function count;

final class Responses implements JsonSerializable
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Response> $responses
     */
    public function __construct(
        private array $responses,
        private ?Response $default = null,
    ) {
        if ($this->default === null && count($this->responses) === 0) {
            throw new InvalidArgumentException('Responses must have at least one response.');
        }
    }

    /**
     * @return iterable<string, \Netgen\IbexaOpenApi\OpenApi\Model\Response>
     */
    public function jsonSerialize(): iterable
    {
        if ($this->default !== null) {
            yield 'default' => $this->default;
        }

        yield from $this->responses;
    }
}
