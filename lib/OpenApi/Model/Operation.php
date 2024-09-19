<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use function count;

final class Operation
{
    /**
     * @param \Netgen\IbexaOpenApi\OpenApi\Model\Parameter[]|null $parameters
     */
    public function __construct(
        private ?array $parameters,
        private ?RequestBody $requestBody,
        private ?Responses $responses,
    ) {}

    /**
     * @return \Netgen\IbexaOpenApi\OpenApi\Model\Parameter[]|null
     */
    public function getParameters(): ?array
    {
        if ($this->parameters !== null && count($this->parameters) === 0) {
            return null;
        }

        return $this->parameters;
    }

    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }

    public function getResponses(): ?Responses
    {
        return $this->responses;
    }
}
