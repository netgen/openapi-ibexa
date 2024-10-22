<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_keys;

final class RelationFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'destinationContentId' => new Schema\OneOfSchema(
                [
                    new Schema\IntegerSchema(),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
