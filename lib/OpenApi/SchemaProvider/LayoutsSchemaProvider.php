<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Netgen\Layouts\Block\ContainerDefinitionInterface;
use Netgen\Layouts\Block\Registry\BlockDefinitionRegistry;
use Netgen\Layouts\Layout\Registry\LayoutTypeRegistry;
use Netgen\Layouts\Layout\Type\LayoutTypeInterface;
use Netgen\OpenApi\Model\Discriminator;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;

use function array_keys;
use function array_map;
use function sprintf;
use function Symfony\Component\String\u;

final class LayoutsSchemaProvider implements SchemaProviderInterface
{
    public function __construct(
        private LayoutTypeRegistry $layoutTypeRegistry,
        private BlockDefinitionRegistry $blockDefinitionRegistry,
    ) {}

    public function provideSchemas(): iterable
    {
        $innerLayoutTypeSchemas = $this->buildInnerLayoutTypeSchemas();
        $layoutTypeSchemas = $this->buildLayoutTypeSchemas();

        yield from [
            'Layouts.BaseLayout' => $this->buildBaseLayoutSchema(),
            'Layouts.Layout' => $this->buildLayoutSchema($innerLayoutTypeSchemas, $layoutTypeSchemas),
        ];

        yield from $innerLayoutTypeSchemas;

        yield from $layoutTypeSchemas;

        yield from [
            'Layouts.Zone' => $this->buildZoneSchema(),
            'Layouts.BaseBlock' => $this->buildBaseBlockSchema(),
            'Layouts.Block' => $this->buildBlockSchema(),
            'Layouts.Placeholder' => $this->buildPlaceholderSchema(),
        ];

        yield from $this->buildTitleBlockSchemas();

        yield from $this->buildListBlockSchemas();

        yield from $this->buildComponentBlockSchemas();

        $innerPlaceholderBlockSchemas = $this->buildInnerPlaceholderBlockSchemas();
        $placeholderBlockSchemas = $this->buildPlaceholderBlockSchemas();

        yield from [
            'Layouts.Block.Placeholder' => $this->buildPlaceholderBlockMainSchema($innerPlaceholderBlockSchemas, $placeholderBlockSchemas),
        ];

        yield from $placeholderBlockSchemas;

        yield from $innerPlaceholderBlockSchemas;
    }

    private function buildBaseLayoutSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\StringSchema(null, null, Schema\Format::Uuid),
            'layoutType' => new Schema\StringSchema(),
            'name' => new Schema\StringSchema(),
            'description' => new Schema\StringSchema(),
            'creationDate' => new Schema\IntegerSchema(),
            'modificationDate' => new Schema\IntegerSchema(),
            'isShared' => new Schema\BooleanSchema(),
            'mainLocale' => new Schema\StringSchema(),
            'availableLocales' => new Schema\ArraySchema(new Schema\StringSchema()),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildInnerLayoutTypeSchemas(): array
    {
        $layoutTypeSchemas = [];

        foreach ($this->layoutTypeRegistry->getLayoutTypes(true) as $layoutType) {
            $schemaName = sprintf('Layouts.Layout.%s.Inner', u($layoutType->getName())->camel()->title());
            $schema = new Schema\ObjectSchema(
                [
                    'layoutType' => new Schema\StringSchema(null, $layoutType->getIdentifier()),
                    'zones' => $this->buildZonesSchema($layoutType),
                ],
                null,
                ['layoutType', 'zones'],
            );

            $layoutTypeSchemas[$schemaName] = $schema;
        }

        return $layoutTypeSchemas;
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema>
     */
    private function buildLayoutTypeSchemas(): array
    {
        $layoutTypeSchemas = [];

        foreach ($this->layoutTypeRegistry->getLayoutTypes(true) as $layoutType) {
            $schemaName = sprintf('Layouts.Layout.%s', u($layoutType->getName())->camel()->title());
            $schema = new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseLayout'),
                    new Schema\ReferenceSchema(sprintf('%s.Inner', $schemaName)),
                ],
            );

            $layoutTypeSchemas[$schemaName] = $schema;
        }

        return $layoutTypeSchemas;
    }

    /**
     * @param array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema> $innerLayoutTypeSchemas
     * @param array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema> $layoutTypeSchemas
     */
    private function buildLayoutSchema(array $innerLayoutTypeSchemas, array $layoutTypeSchemas): Schema\OneOfSchema
    {
        $discriminatorMappings = [];

        foreach ($layoutTypeSchemas as $schemaName => $schema) {
            $innerSchema = $innerLayoutTypeSchemas[sprintf('%s.Inner', $schemaName)];

            /** @var \Netgen\OpenApi\Model\Schema\StringSchema $layoutTypeFieldSchema */
            $layoutTypeFieldSchema = ($innerSchema->getProperties() ?? [])['layoutType'];

            $discriminatorMappings[$layoutTypeFieldSchema->getConst() ?? ''] = $schemaName;
        }

        $discriminator = new Discriminator('layoutType', $discriminatorMappings);

        return new Schema\OneOfSchema(
            array_map(
                static fn (string $schemaName): Schema\ReferenceSchema => new Schema\ReferenceSchema($schemaName),
                array_keys($layoutTypeSchemas),
            ),
            $discriminator,
        );
    }

    private function buildZonesSchema(LayoutTypeInterface $layoutType): Schema\ObjectSchema
    {
        $zoneSchemas = [];

        foreach ($layoutType->getZones() as $zone) {
            $zoneSchemas[$zone->getIdentifier()] = new Schema\ReferenceSchema('Layouts.Zone');
        }

        return new Schema\ObjectSchema($zoneSchemas, null, array_keys($zoneSchemas));
    }

    private function buildZoneSchema(): Schema\ObjectSchema
    {
        $properties = [
            'blocks' => new Schema\ArraySchema(new Schema\ReferenceSchema('Layouts.Block')),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildBaseBlockSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\StringSchema(null, null, Schema\Format::Uuid),
            'type' => new Schema\StringSchema(),
            'viewType' => new Schema\StringSchema(),
            'itemViewType' => new Schema\StringSchema(),
            'cssClass' => new Schema\StringSchema(),
            'cssId' => new Schema\StringSchema(),
            'setContainer' => new Schema\BooleanSchema(),
            'setContainerSize' => new Schema\StringSchema(),
            'verticalWhitespaceEnabled' => new Schema\BooleanSchema(),
            'verticalWhitespaceTop' => new Schema\StringSchema(),
            'verticalWhitespaceBottom' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildBlockSchema(): Schema\OneOfSchema
    {
        return new Schema\OneOfSchema(
            [
                new Schema\ReferenceSchema('Layouts.Block.Title'),
                new Schema\ReferenceSchema('Layouts.Block.List'),
                new Schema\ReferenceSchema('Layouts.Block.Component'),
                new Schema\ReferenceSchema('Layouts.Block.Placeholder'),
            ],
            new Discriminator(
                'type',
                [
                    'title' => 'Layouts.Block.Title',
                    'list' => 'Layouts.Block.List',
                    'component' => 'Layouts.Block.Component',
                    'placeholder' => 'Layouts.Block.Placeholder',
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

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema>
     */
    private function buildTitleBlockSchemas(): array
    {
        $properties = [
            'type' => new Schema\StringSchema(null, 'title'),
            'tag' => new Schema\StringSchema(),
            'title' => new Schema\StringSchema(),
        ];

        return [
            'Layouts.Block.Title.Inner' => new Schema\ObjectSchema($properties, null, array_keys($properties)),
            'Layouts.Block.Title' => new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseBlock'),
                    new Schema\ReferenceSchema('Layouts.Block.Title.Inner'),
                ],
            ),
        ];
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema>
     */
    private function buildListBlockSchemas(): array
    {
        $properties = [
            'type' => new Schema\StringSchema(null, 'list'),
            'columns' => new Schema\IntegerSchema(),
            'items' => new Schema\ArraySchema(
                $this->buildBlockItemSchema(),
            ),
        ];

        return [
            'Layouts.Block.List.Inner' => new Schema\ObjectSchema($properties, null, array_keys($properties)),
            'Layouts.Block.List' => new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseBlock'),
                    new Schema\ReferenceSchema('Layouts.Block.List.Inner'),
                ],
            ),
        ];
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema>
     */
    private function buildComponentBlockSchemas(): array
    {
        $properties = [
            'type' => new Schema\StringSchema(null, 'component'),
            'componentType' => new Schema\StringSchema(),
            'content' => new Schema\ReferenceSchema('SiteApi.Content'),
        ];

        return [
            'Layouts.Block.Component.Inner' => new Schema\ObjectSchema($properties, null, array_keys($properties)),
            'Layouts.Block.Component' => new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseBlock'),
                    new Schema\ReferenceSchema('Layouts.Block.Component.Inner'),
                ],
            ),
        ];
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildInnerPlaceholderBlockSchemas(): array
    {
        $placeholderBlockSchemas = [];

        foreach ($this->blockDefinitionRegistry->getBlockDefinitions() as $blockDefinition) {
            if (!$blockDefinition instanceof ContainerDefinitionInterface) {
                continue;
            }

            $schemaName = sprintf('Layouts.Block.Placeholder.%s.Inner', u($blockDefinition->getName())->camel()->title());
            $schema = new Schema\ObjectSchema(
                [
                    'type' => new Schema\StringSchema(null, 'placeholder'),
                    'placeholderType' => new Schema\StringSchema(null, $blockDefinition->getIdentifier()),
                    'placeholders' => $this->buildPlaceholdersSchema($blockDefinition),
                ],
                null,
                ['type', 'placeholderType', 'placeholders'],
            );

            $placeholderBlockSchemas[$schemaName] = $schema;
        }

        return $placeholderBlockSchemas;
    }

    /**
     * @param array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema> $innerPlaceholderBlockSchemas
     * @param array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema> $placeholderBlockSchemas
     */
    private function buildPlaceholderBlockMainSchema(array $innerPlaceholderBlockSchemas, array $placeholderBlockSchemas): Schema\OneOfSchema
    {
        $discriminatorMappings = [];

        foreach ($placeholderBlockSchemas as $schemaName => $schema) {
            $innerSchema = $innerPlaceholderBlockSchemas[sprintf('%s.Inner', $schemaName)];

            /** @var \Netgen\OpenApi\Model\Schema\StringSchema $placeholderTypeFieldSchema */
            $placeholderTypeFieldSchema = ($innerSchema->getProperties() ?? [])['placeholderType'];

            $discriminatorMappings[$placeholderTypeFieldSchema->getConst() ?? ''] = $schemaName;
        }

        $discriminator = new Discriminator('placeholderType', $discriminatorMappings);

        return new Schema\OneOfSchema(
            array_map(
                static fn (string $schemaName): Schema\ReferenceSchema => new Schema\ReferenceSchema($schemaName),
                array_keys($placeholderBlockSchemas),
            ),
            $discriminator,
        );
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema>
     */
    private function buildPlaceholderBlockSchemas(): array
    {
        $placeholderBlockSchemas = [];

        foreach ($this->blockDefinitionRegistry->getBlockDefinitions() as $blockDefinition) {
            if (!$blockDefinition instanceof ContainerDefinitionInterface) {
                continue;
            }

            $schemaName = sprintf('Layouts.Block.Placeholder.%s', u($blockDefinition->getName())->camel()->title());
            $schema = new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseBlock'),
                    new Schema\ReferenceSchema(sprintf('%s.Inner', $schemaName)),
                ],
            );

            $placeholderBlockSchemas[$schemaName] = $schema;
        }

        return $placeholderBlockSchemas;
    }

    private function buildPlaceholdersSchema(ContainerDefinitionInterface $containerDefinition): Schema\ObjectSchema
    {
        $placeholderSchemas = [];

        foreach ($containerDefinition->getPlaceholders() as $placeholder) {
            $placeholderSchemas[$placeholder] = new Schema\ReferenceSchema('Layouts.Placeholder');
        }

        return new Schema\ObjectSchema($placeholderSchemas, null, array_keys($placeholderSchemas));
    }

    private function buildPlaceholderSchema(): Schema\ObjectSchema
    {
        $properties = [
            'blocks' => new Schema\ArraySchema(new Schema\ReferenceSchema('Layouts.Block')),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
