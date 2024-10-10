<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Netgen\IbexaOpenApi\OpenApi\Model\Discriminator;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;

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
        $contentTypeSchemas = $this->buildContentTypeSchemas();

        yield from [
            'SiteApi.Content' => $this->buildContentSchema($contentTypeSchemas),
            'SiteApi.BaseContent' => $this->buildBaseContentSchema(),
            'SiteApi.Location' => $this->buildLocationSchema(),
        ];

        yield from $contentTypeSchemas;
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema\AllOfSchema>
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
                        new Schema\ObjectSchema(
                            [
                                'contentType' => new Schema\StringSchema(null, $contentType->identifier),
                                'fields' => new Schema\ObjectSchema($this->buildFieldDefinitionSchemas($contentType)),
                            ],
                            null,
                            ['contentType', 'fields'],
                        ),
                    ],
                );

                $contentTypeSchemas[$schemaName] = $schema;
            }
        }

        return $contentTypeSchemas;
    }

    /**
     * @return array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema\ObjectSchema>
     */
    private function buildFieldDefinitionSchemas(ContentType $contentType): array
    {
        $fieldSchemas = [];

        foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
            $fieldSchemas[$fieldDefinition->getIdentifier()] = $this->buildFieldDefinitionSchema($fieldDefinition);
        }

        return $fieldSchemas;
    }

    private function buildFieldDefinitionSchema(FieldDefinition $fieldDefinition): Schema\ObjectSchema
    {
        $properties = [
            'fieldType' => new Schema\StringSchema(null, $fieldDefinition->fieldTypeIdentifier),
            'isEmpty' => new Schema\BooleanSchema(),
            'value' => new Schema\ObjectSchema(),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    /**
     * @param array<string, \Netgen\IbexaOpenApi\OpenApi\Model\Schema\AllOfSchema> $contentTypeSchemas
     */
    private function buildContentSchema(array $contentTypeSchemas): Schema\OneOfSchema
    {
        $discriminatorMappings = [];

        foreach ($contentTypeSchemas as $schemaName => $schema) {
            /** @var \Netgen\IbexaOpenApi\OpenApi\Model\Schema\ObjectSchema $contentTypeSchema */
            $contentTypeSchema = $schema->getAllOf()[1];

            /** @var \Netgen\IbexaOpenApi\OpenApi\Model\Schema\StringSchema $contentTypeFieldSchema */
            $contentTypeFieldSchema = ($contentTypeSchema->getProperties() ?? [])['contentType'];

            $discriminatorMappings[$contentTypeFieldSchema->getConst()] = $schemaName;
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
