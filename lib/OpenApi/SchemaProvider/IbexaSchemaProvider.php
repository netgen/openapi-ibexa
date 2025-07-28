<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Netgen\OpenApi\Model\Schema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;
use Traversable;

use function array_keys;
use function iterator_to_array;
use function sprintf;
use function Symfony\Component\String\u;

final class IbexaSchemaProvider implements SchemaProviderInterface
{
    /**
     * @var array<string, \Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue\FieldValueSchemaProviderInterface>
     */
    private array $fieldValueSchemaProviders;

    /**
     * @param iterable<\Netgen\OpenApiIbexa\OpenApi\SchemaProvider\Ibexa\FieldValue\FieldValueSchemaProviderInterface> $fieldValueSchemaProviders
     */
    public function __construct(
        private FieldTypeService $fieldTypeService,
        iterable $fieldValueSchemaProviders,
    ) {
        $this->fieldValueSchemaProviders = $fieldValueSchemaProviders instanceof Traversable ?
            iterator_to_array($fieldValueSchemaProviders) :
            $fieldValueSchemaProviders;
    }

    public function provideSchemas(): iterable
    {
        foreach ($this->fieldTypeService->getFieldTypes() as $fieldType) {
            $fieldName = u($fieldType->getFieldTypeIdentifier())->camel()->title();

            yield sprintf('Ibexa.Field.%s', $fieldName) => $this->buildFieldTypeSchema($fieldType->getFieldTypeIdentifier());
        }
    }

    private function buildFieldTypeSchema(string $fieldTypeIdentifier): Schema\ObjectSchema
    {
        $properties = [
            'fieldType' => new Schema\StringSchema(null, $fieldTypeIdentifier),
            'isEmpty' => new Schema\BooleanSchema(),
            'value' => $this->buildFieldValueSchema($fieldTypeIdentifier),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildFieldValueSchema(string $fieldTypeIdentifier): Schema
    {
        $fieldValueSchemaProvider = $this->fieldValueSchemaProviders[$fieldTypeIdentifier] ?? null;

        return $fieldValueSchemaProvider?->provideFieldValueSchema() ?? new Schema\ObjectSchema();
    }
}
