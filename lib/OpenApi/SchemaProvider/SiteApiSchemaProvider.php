<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroup;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Netgen\OpenApi\Model\Discriminator;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;

use function array_keys;
use function array_map;
use function sprintf;
use function Symfony\Component\String\u;
use function usort;

final class SiteApiSchemaProvider implements SchemaProviderInterface
{
    /**
     * @param array<string, \Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\ContentType\ContentTypeSchemaProviderInterface[]> $contentTypeSchemaProviders
     */
    public function __construct(
        private ContentTypeService $contentTypeService,
        private array $contentTypeSchemaProviders,
    ) {}

    public function provideSchemas(): iterable
    {
        $innerContentTypeSchemas = $this->buildInnerContentTypeSchemas();
        $contentTypeSchemas = $this->buildContentTypeSchemas();

        yield from [
            'SiteApi.BaseContent' => $this->buildBaseContentSchema(),
            'SiteApi.Content' => $this->buildContentSchema($innerContentTypeSchemas, $contentTypeSchemas),
            'SiteApi.ContentList' => $this->buildContentListSchema(),
            'SiteApi.Location' => $this->buildLocationSchema(),
            'SiteApi.LocationList' => $this->buildLocationListSchema(),
            'SiteApi.ContentAndLocation' => $this->buildContentAndLocationSchema(),
            'SiteApi.Queries' => $this->buildQueriesSchema(),
        ];

        yield from $innerContentTypeSchemas;

        yield from $contentTypeSchemas;
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildInnerContentTypeSchemas(): array
    {
        $contentTypeSchemas = [];

        $contentTypeGroups = (array) $this->contentTypeService->loadContentTypeGroups();

        usort($contentTypeGroups, static fn (ContentTypeGroup $a, ContentTypeGroup $b): int => $a->identifier <=> $b->identifier);

        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = (array) $this->contentTypeService->loadContentTypes($contentTypeGroup);

            usort($contentTypes, static fn (ContentType $a, ContentType $b): int => $a->identifier <=> $b->identifier);

            foreach ($contentTypes as $contentType) {
                $additionalSchemas = [];

                foreach ($this->contentTypeSchemaProviders[$contentType->getIdentifier()] ?? [] as $contentTypeSchemaProvider) {
                    $additionalSchemas = [...$additionalSchemas, ...$contentTypeSchemaProvider->provideContentTypeSchemas()];
                }

                $schemaName = sprintf('SiteApi.Content.%s.Inner', u($contentType->getName())->camel()->title());
                $schema = new Schema\ObjectSchema(
                    [
                        'contentType' => new Schema\StringSchema(null, $contentType->identifier),
                        'fields' => $this->buildFieldsSchema($contentType),
                    ] + $additionalSchemas,
                    null,
                    ['contentType', 'fields', ...array_keys($additionalSchemas)],
                );

                $contentTypeSchemas[$schemaName] = $schema;
            }
        }

        return $contentTypeSchemas;
    }

    /**
     * @return array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema>
     */
    private function buildContentTypeSchemas(): array
    {
        $contentTypeSchemas = [];

        $contentTypeGroups = (array) $this->contentTypeService->loadContentTypeGroups();

        usort($contentTypeGroups, static fn (ContentTypeGroup $a, ContentTypeGroup $b): int => $a->identifier <=> $b->identifier);

        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = (array) $this->contentTypeService->loadContentTypes($contentTypeGroup);

            usort($contentTypes, static fn (ContentType $a, ContentType $b): int => $a->identifier <=> $b->identifier);

            foreach ($contentTypes as $contentType) {
                $schemaName = sprintf('SiteApi.Content.%s', u($contentType->getName())->camel()->title());
                $schema = new Schema\AllOfSchema(
                    [
                        new Schema\ReferenceSchema('SiteApi.BaseContent'),
                        new Schema\ReferenceSchema(sprintf('%s.Inner', $schemaName)),
                    ],
                );

                $contentTypeSchemas[$schemaName] = $schema;
            }
        }

        return $contentTypeSchemas;
    }

    private function buildFieldsSchema(ContentType $contentType): Schema\ObjectSchema
    {
        $fieldSchemas = [];

        $fieldDefinitions = $contentType->getFieldDefinitions()->toArray();

        usort($fieldDefinitions, static fn (FieldDefinition $a, FieldDefinition $b): int => $a->identifier <=> $b->identifier);

        foreach ($fieldDefinitions as $fieldDefinition) {
            $fieldName = u($fieldDefinition->getFieldTypeIdentifier())->camel()->title();
            $fieldSchemas[$fieldDefinition->getIdentifier()] = new Schema\ReferenceSchema(
                sprintf('Ibexa.Field.%s', $fieldName),
            );
        }

        return new Schema\ObjectSchema($fieldSchemas);
    }

    /**
     * @param array<string, \Netgen\OpenApi\Model\Schema\ObjectSchema> $innerContentTypeSchemas
     * @param array<string, \Netgen\OpenApi\Model\Schema\AllOfSchema> $contentTypeSchemas
     */
    private function buildContentSchema(array $innerContentTypeSchemas, array $contentTypeSchemas): Schema\OneOfSchema
    {
        $discriminatorMappings = [];

        foreach ($contentTypeSchemas as $schemaName => $schema) {
            $innerSchema = $innerContentTypeSchemas[sprintf('%s.Inner', $schemaName)];

            /** @var \Netgen\OpenApi\Model\Schema\StringSchema $contentTypeFieldSchema */
            $contentTypeFieldSchema = ($innerSchema->getProperties() ?? [])['contentType'];

            $discriminatorMappings[$contentTypeFieldSchema->getConst() ?? ''] = $schemaName;
        }

        $discriminator = new Discriminator('contentType', $discriminatorMappings);

        return new Schema\OneOfSchema(
            array_map(
                static fn (string $schemaName): Schema\ReferenceSchema => new Schema\ReferenceSchema($schemaName),
                array_keys($contentTypeSchemas),
            ),
            $discriminator,
        );
    }

    private function buildBaseContentSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\IntegerSchema(),
            'type' => new Schema\StringSchema(null, 'content'),
            'remoteId' => new Schema\StringSchema(),
            'mainLocationId' => new Schema\IntegerSchema(),
            'name' => new Schema\StringSchema(),
            'languageCode' => new Schema\StringSchema(),
            'contentType' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildLocationSchema(): Schema\ObjectSchema
    {
        $properties = [
            'id' => new Schema\IntegerSchema(),
            'type' => new Schema\StringSchema(null, 'location'),
            'remoteId' => new Schema\StringSchema(),
            'contentId' => new Schema\IntegerSchema(),
            'parentLocationId' => new Schema\IntegerSchema(),
            'pathString' => new Schema\StringSchema(),
            'pathArray' => new Schema\ArraySchema(new Schema\IntegerSchema()),
            'depth' => new Schema\IntegerSchema(),
            'path' => new Schema\StringSchema(),
            'url' => new Schema\StringSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildContentListSchema(): Schema\ArraySchema
    {
        return new Schema\ArraySchema(
            new Schema\ReferenceSchema('SiteApi.ContentAndLocation'),
        );
    }

    private function buildLocationListSchema(): Schema\ArraySchema
    {
        return new Schema\ArraySchema(
            new Schema\ReferenceSchema('SiteApi.ContentAndLocation'),
        );
    }

    private function buildContentAndLocationSchema(): Schema\ObjectSchema
    {
        $properties = [
            'content' => new Schema\ReferenceSchema('SiteApi.Content'),
            'location' => new Schema\OneOfSchema(
                [
                    new Schema\ReferenceSchema('SiteApi.Location'),
                    new Schema\NullSchema(),
                ],
            ),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildQueriesSchema(): Schema\ObjectSchema
    {
        return new Schema\ObjectSchema(
            null,
            null,
            null,
            null,
            new Schema\StringSchema(null, null, Schema\Format::IriReference),
        );
    }
}
