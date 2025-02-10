<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\Layouts\Block\BlockDefinition\Handler\DynamicParameter;
use Netgen\Layouts\Block\BlockDefinitionInterface;
use Netgen\Layouts\Block\ContainerDefinitionInterface;
use Netgen\Layouts\Block\Registry\BlockDefinitionRegistry;
use Netgen\Layouts\Layout\Registry\LayoutTypeRegistry;
use Netgen\Layouts\Layout\Type\LayoutTypeInterface;
use Netgen\Layouts\Parameters\CompoundParameterDefinition;
use Netgen\Layouts\Parameters\ParameterTypeInterface;
use Netgen\Layouts\Parameters\Registry\ParameterTypeRegistry;
use Netgen\OpenApi\Model\Discriminator;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;
use ReflectionClass;
use Traversable;

use function array_keys;
use function array_map;
use function iterator_to_array;
use function sprintf;
use function Symfony\Component\String\u;

final class LayoutsSchemaProvider implements SchemaProviderInterface
{
    /**
     * @var array<string, \Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts\ParameterValueSchemaProviderInterface>
     */
    private array $parameterValueSchemaProviders;

    /**
     * @param iterable<\Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Layouts\ParameterValueSchemaProviderInterface> $parameterValueSchemaProviders
     */
    public function __construct(
        private LayoutTypeRegistry $layoutTypeRegistry,
        private BlockDefinitionRegistry $blockDefinitionRegistry,
        private ParameterTypeRegistry $parameterTypeRegistry,
        private ConfigResolverInterface $configResolver,
        iterable $parameterValueSchemaProviders,
    ) {
        $this->parameterValueSchemaProviders = $parameterValueSchemaProviders instanceof Traversable ?
            iterator_to_array($parameterValueSchemaProviders) :
            $parameterValueSchemaProviders;
    }

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

        yield 'Layouts.Zone' => $this->buildZoneSchema();

        $innerBlockDefinitionSchemas = $this->buildInnerBlockDefinitionSchemas();
        $blockDefinitionSchemas = $this->buildBlockDefinitionSchemas();

        yield from [
            'Layouts.BaseBlock' => $this->buildBaseBlockSchema(),
            'Layouts.Block' => $this->buildBlockSchema($innerBlockDefinitionSchemas, $blockDefinitionSchemas),
        ];

        yield from $innerBlockDefinitionSchemas;

        yield from $blockDefinitionSchemas;

        yield 'Layouts.Placeholder' => $this->buildPlaceholderSchema();

        yield from $this->buildParameterTypeSchemas();
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

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildInnerBlockDefinitionSchemas(): array
    {
        $blockDefinitionSchemas = [];

        $blockDefinitions = $this->blockDefinitionRegistry->getBlockDefinitions();

        foreach ($blockDefinitions as $blockDefinition) {
            $properties = [
                'definitionIdentifier' => new Schema\StringSchema(null, $blockDefinition->getIdentifier()),
                'parameters' => $this->buildParametersSchema($blockDefinition),
                'dynamicParameters' => $this->buildDynamicParametersSchema($blockDefinition),
            ];

            if ($blockDefinition->hasCollection('default')) {
                $properties['items'] = new Schema\ArraySchema(
                    new Schema\ReferenceSchema('SiteApi.ContentAndLocation'),
                );
            }

            if ($blockDefinition instanceof ContainerDefinitionInterface) {
                $properties['placeholders'] = $this->buildPlaceholdersSchema($blockDefinition);
            }

            $schemaName = sprintf('Layouts.Block.%s.Inner', u($blockDefinition->getIdentifier())->camel()->title());
            $schema = new Schema\ObjectSchema($properties, null, array_keys($properties));

            $blockDefinitionSchemas[$schemaName] = $schema;
        }

        return $blockDefinitionSchemas;
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema>
     */
    private function buildBlockDefinitionSchemas(): array
    {
        $blockDefinitionSchemas = [];

        $blockDefinitions = $this->blockDefinitionRegistry->getBlockDefinitions();

        foreach ($blockDefinitions as $blockDefinition) {
            $schemaName = sprintf('Layouts.Block.%s', u($blockDefinition->getIdentifier())->camel()->title());
            $schema = new Schema\AllOfSchema(
                [
                    new Schema\ReferenceSchema('Layouts.BaseBlock'),
                    new Schema\ReferenceSchema(sprintf('%s.Inner', $schemaName)),
                ],
            );

            $blockDefinitionSchemas[$schemaName] = $schema;
        }

        return $blockDefinitionSchemas;
    }

    private function buildParametersSchema(BlockDefinitionInterface $blockDefinition): Schema\ObjectSchema
    {
        $parameterSchemas = [];

        foreach ($blockDefinition->getParameterDefinitions() as $parameterDefinition) {
            $parameterName = u($parameterDefinition->getType()::getIdentifier())->camel()->title();
            $parameterSchemas[$parameterDefinition->getName()] = new Schema\ReferenceSchema(
                sprintf('Layouts.Parameter.%s', $parameterName),
            );

            if ($parameterDefinition instanceof CompoundParameterDefinition) {
                foreach ($parameterDefinition->getParameterDefinitions() as $innerParameterDefinition) {
                    $innerParameterName = u($innerParameterDefinition->getType()::getIdentifier())->camel()->title();
                    $parameterSchemas[$innerParameterDefinition->getName()] = new Schema\ReferenceSchema(
                        sprintf('Layouts.Parameter.%s', $innerParameterName),
                    );
                }
            }
        }

        return new Schema\ObjectSchema($parameterSchemas);
    }

    private function buildDynamicParametersSchema(BlockDefinitionInterface $blockDefinition): Schema\ObjectSchema
    {
        $schemaNames = $this->configResolver->getParameter(
            'layouts_dynamic_parameters_schema',
            'netgen_open_api_ibexa',
        )[$blockDefinition->getIdentifier()] ?? [];

        $parameterSchemas = [
            ...$this->getClassDynamicParameterSchemas($blockDefinition->getHandler()::class, $schemaNames),
        ];

        foreach ($blockDefinition->getPlugins() as $plugin) {
            $parameterSchemas += [
                ...$this->getClassDynamicParameterSchemas($plugin::class, $schemaNames),
            ];
        }

        return new Schema\ObjectSchema($parameterSchemas);
    }

    /**
     * @param class-string $className
     * @param array<string, array{reference_name: string}> $schemaNames
     *
     * @return iterable<string, \Netgen\OpenApi\Model\Schema\ObjectSchema|\Netgen\OpenApi\Model\Schema\ReferenceSchema>
     */
    private function getClassDynamicParameterSchemas(string $className, array $schemaNames): iterable
    {
        $reflectionClass = new ReflectionClass($className);
        $reflectionAttributes = $reflectionClass->getAttributes(DynamicParameter::class);

        foreach ($reflectionAttributes as $reflectionAttribute) {
            /** @var \Netgen\Layouts\Block\BlockDefinition\Handler\DynamicParameter $attribute */
            $attribute = $reflectionAttribute->newInstance();

            $schema = isset($schemaNames[$attribute->parameterName]['reference_name']) ?
                new Schema\ReferenceSchema($schemaNames[$attribute->parameterName]['reference_name']) :
                new Schema\ObjectSchema();

            yield $attribute->parameterName => $schema;
        }
    }

    private function buildBaseBlockSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\StringSchema(null, null, Schema\Format::Uuid),
            'definitionIdentifier' => new Schema\StringSchema(),
            'viewType' => new Schema\StringSchema(),
            'itemViewType' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    /**
     * @param array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema> $innerBlockDefinitionSchemas
     * @param array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema> $blockDefinitionSchemas
     */
    private function buildBlockSchema(array $innerBlockDefinitionSchemas, array $blockDefinitionSchemas): Schema\OneOfSchema
    {
        $discriminatorMappings = [];

        foreach ($blockDefinitionSchemas as $schemaName => $schema) {
            $innerSchema = $innerBlockDefinitionSchemas[sprintf('%s.Inner', $schemaName)];

            /** @var \Netgen\OpenApi\Model\Schema\StringSchema $definitionIdentifierFieldSchema */
            $definitionIdentifierFieldSchema = ($innerSchema->getProperties() ?? [])['definitionIdentifier'];

            $discriminatorMappings[$definitionIdentifierFieldSchema->getConst() ?? ''] = $schemaName;
        }

        $discriminator = new Discriminator('definitionIdentifier', $discriminatorMappings);

        return new Schema\OneOfSchema(
            array_map(
                static fn (string $schemaName): Schema\ReferenceSchema => new Schema\ReferenceSchema($schemaName),
                array_keys($blockDefinitionSchemas),
            ),
            $discriminator,
        );
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

    /**
     * @return iterable<string, \Netgen\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildParameterTypeSchemas(): iterable
    {
        foreach ($this->parameterTypeRegistry->getParameterTypes() as $parameterType) {
            $parameterTypeName = u($parameterType::getIdentifier())->camel()->title();

            yield sprintf('Layouts.Parameter.%s', $parameterTypeName) => $this->buildParameterTypeSchema($parameterType);
        }
    }

    private function buildParameterTypeSchema(ParameterTypeInterface $parameterType): Schema\ObjectSchema
    {
        $properties = [
            'parameterType' => new Schema\StringSchema(null, $parameterType::getIdentifier()),
            'isEmpty' => new Schema\BooleanSchema(),
            'value' => $this->buildParameterValueSchema($parameterType),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildParameterValueSchema(ParameterTypeInterface $parameterType): Schema
    {
        $parameterValueSchemaProvider = $this->parameterValueSchemaProviders[$parameterType::getIdentifier()] ?? null;

        return $parameterValueSchemaProvider?->provideParameterValueSchema() ?? new Schema\ObjectSchema();
    }
}
