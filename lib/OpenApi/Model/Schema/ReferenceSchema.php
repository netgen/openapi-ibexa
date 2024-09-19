<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use JsonSerializable;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function sprintf;

final class ReferenceSchema extends Schema implements JsonSerializable
{
    public function __construct(
        private string $schemaName,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            '$ref' => sprintf('#/components/schemas/%s', $this->schemaName),
        ];
    }
}
