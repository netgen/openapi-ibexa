<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_keys;

final class RelationListFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'destinationContentIds' => new Schema\ArraySchema(new Schema\IntegerSchema()),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
