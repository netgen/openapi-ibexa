<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Netgen\IbexaOpenApi\OpenApi\Model\Discriminator;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;

use function array_keys;

final class LayoutsSchemaProvider implements SchemaProviderInterface
{
    public function provideSchemas(): iterable
    {
        return [
            'Layouts.Layout' => $this->buildLayoutSchema(),
            'Layouts.Block.Title' => $this->buildTitleBlockSchema(),
            'Layouts.Block.List' => $this->buildListBlockSchema(),
            'Layouts.Block.Component' => $this->buildComponentBlockSchema(),
        ];
    }

    private function buildLayoutSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\StringSchema(),
            'type' => new Schema\StringSchema(),
            'name' => new Schema\StringSchema(),
            'description' => new Schema\StringSchema(),
            'creation_date' => new Schema\IntegerSchema(),
            'modification_date' => new Schema\IntegerSchema(),
            'is_shared' => new Schema\BooleanSchema(),
            'main_locale' => new Schema\StringSchema(),
            'available_locales' => new Schema\ArraySchema(new Schema\StringSchema()),
            'zones' => $this->buildZonesSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildZonesSchema(): Schema\ObjectSchema
    {
        $patternProperties = [
            '^[A-Za-z0-9_]*[A-Za-z][A-Za-z0-9_]*$' => $this->buildZoneSchema(),
        ];

        return new Schema\ObjectSchema(null, $patternProperties);
    }

    private function buildZoneSchema(): Schema\ObjectSchema
    {
        $properties = [
            'identifier' => new Schema\StringSchema(),
            'blocks' => new Schema\ArraySchema($this->buildBlockSchema()),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildBlockSchema(): Schema\OneOfSchema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\ReferenceSchema('Layouts.Block.Title'),
                new Schema\ReferenceSchema('Layouts.Block.List'),
                new Schema\ReferenceSchema('Layouts.Block.Grid'),
                new Schema\ReferenceSchema('Layouts.Block.Component'),
            ],
            new Discriminator(
                'type',
                [
                    'title' => 'Layouts.Block.Title',
                    'list' => 'Layouts.Block.List',
                    'grid' => 'Layouts.Block.Grid',
                    'component' => 'Layouts.Block.Component',
                ],
            ),
        );
    }

    private function buildBlockItemSchema(): Schema\OneOfSchema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\ReferenceSchema('SiteApi.Content'),
                new Schema\ReferenceSchema('SiteApi.Location'),
            ],
            new Discriminator(
                'type',
                [
                    'content' => 'SiteApi.Content',
                    'location' => 'SiteApi.Location',
                ],
            ),
        );
    }

    private function buildTitleBlockSchema(): Schema\ObjectSchema
    {
        $properties = [
            'type' => new Schema\StringSchema(),
            'tag' => new Schema\StringSchema(),
            'title' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildListBlockSchema(): Schema\ObjectSchema
    {
        $properties = [
            'type' => new Schema\StringSchema(),
            'columns' => new Schema\IntegerSchema(),
            'items' => new Schema\ArraySchema(
                $this->buildBlockItemSchema(),
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildComponentBlockSchema(): Schema\ObjectSchema
    {
        $properties = [
            'type' => new Schema\StringSchema(),
            'componentType' => new Schema\StringSchema(),
            'content' => new Schema\ReferenceSchema('SiteApi.Content'),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
