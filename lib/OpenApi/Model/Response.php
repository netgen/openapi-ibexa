<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use function count;

final class Response
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Header> $headers
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\MediaType> $content
     */
    public function __construct(
        private string $description,
        private ?array $headers = null,
        private ?array $content = null,
    ) {}

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Header>|null
     */
    public function getHeaders(): ?array
    {
        if ($this->headers !== null && count($this->headers) === 0) {
            return null;
        }

        return $this->headers;
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\MediaType>|null
     */
    public function getContent(): ?array
    {
        if ($this->content !== null && count($this->content) === 0) {
            return null;
        }

        return $this->content;
    }
}
