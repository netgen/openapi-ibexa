<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_keys;

final class UrlFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'link' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(null, null, Schema\Format::Uri),
                    new Schema\NullSchema(),
                ],
            ),
            'text' => new Schema\OneOfSchema(
                [
                    new Schema\StringSchema(),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
