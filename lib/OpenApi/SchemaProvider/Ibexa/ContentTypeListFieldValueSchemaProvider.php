<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class ContentTypeListFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'identifiers' => new Schema\ArraySchema(new Schema\StringSchema()),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
