<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class RequestBody
{
    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\MediaType> $content
     */
    public function __construct(
        private array $content,
        private bool $required = false,
    ) {}

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\MediaType>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }
}
