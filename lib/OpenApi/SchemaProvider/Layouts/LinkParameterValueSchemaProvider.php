<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts;

use Netgen\OpenApi\Model\Schema;

use function array_keys;

final class LinkParameterValueSchemaProvider implements ParameterValueSchemaProviderInterface
{
    public function provideParameterValueSchema(): Schema
    {
        $properties = [
            'link_type' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'link' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'link_suffix' => new Schema\OneOfSchema([new Schema\StringSchema(), new Schema\NullSchema()]),
            'new_window' => new Schema\OneOfSchema([new Schema\BooleanSchema(), new Schema\NullSchema()]),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
