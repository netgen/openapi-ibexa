<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Util;

use JsonSerializable;

use function sprintf;

final class Reference implements JsonSerializable
{
    public function __construct(
        private string $schemaName,
    ) {}

    public function jsonSerialize(): string
    {
        return sprintf('#/components/schemas/%s', $this->schemaName);
    }
}
