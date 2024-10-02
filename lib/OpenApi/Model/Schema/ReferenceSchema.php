<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use JsonSerializable;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;
use Netgen\IbexaOpenApi\OpenApi\Model\Util\Reference;

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
            '$ref' => new Reference($this->schemaName),
        ];
    }
}
