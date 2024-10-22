<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa;

use Netgen\IbexaOpenApi\OpenApi\Model\Schema;

use function array_keys;

final class RichTextFieldValueSchemaProvider implements FieldValueSchemaProviderInterface
{
    public function provideFieldValueSchema(): Schema\ObjectSchema
    {
        $properties = [
            'xml' => new Schema\StringSchema(),
            'html' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
