<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Netgen\OpenApi\Model\Discriminator;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;

use function array_keys;
use function array_map;
use function sprintf;
use function Symfony\Component\String\u;

final class SiteApiSchemaProvider implements SchemaProviderInterface
{
    public function __construct(
        private ContentTypeService $contentTypeService,
    ) {}

    public function provideSchemas(): iterable
    {
        $innerContentTypeSchemas = $this->buildInnerContentTypeSchemas();
        $contentTypeSchemas = $this->buildContentTypeSchemas();

        yield from [
            'SiteApi.BaseContent' => $this->buildBaseContentSchema(),
            'SiteApi.Content' => $this->buildContentSchema($innerContentTypeSchemas, $contentTypeSchemas),
            'SiteApi.Location' => $this->buildLocationSchema(),
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

        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();

        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = $this->contentTypeService->loadContentTypes($contentTypeGroup);

            foreach ($contentTypes as $contentType) {
                $schemaName = sprintf('SiteApi.Content.%s.Inner', u($contentType->getName())->camel()->title());
                $schema = new Schema\ObjectSchema(
                    [
                        'contentType' => new Schema\StringSchema(null, $contentType->identifier),
                        'fields' => $this->buildFieldsSchema($contentType),
                    ],
                    null,
                    ['contentType', 'fields'],
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

        $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();

        foreach ($contentTypeGroups as $contentTypeGroup) {
            $contentTypes = $this->contentTypeService->loadContentTypes($contentTypeGroup);

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

        foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
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
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }
}
