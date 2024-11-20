<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;
use Netgen\OpenApi\Model\Schema;
use Traversable;

use function array_keys;
use function iterator_to_array;
use function sprintf;
use function Symfony\Component\String\u;

final class IbexaSchemaProvider implements SchemaProviderInterface
{
    /**
     * @var array<string, \Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa\FieldValueSchemaProviderInterface>
     */
    private array $fieldValueSchemaProviders;

    /**
     * @param iterable<\Netgen\IbexaOpenApi\OpenApi\SchemaProvider\Ibexa\FieldValueSchemaProviderInterface> $fieldValueSchemaProviders
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

            yield sprintf('Ibexa.Field.%s', $fieldName) => $this->buildFieldDefinitionSchema($fieldType->getFieldTypeIdentifier());
        }
    }

    private function buildFieldDefinitionSchema(string $fieldTypeIdentifier): Schema\ObjectSchema
    {
        $properties = [
            'fieldType' => new Schema\StringSchema(null, $fieldTypeIdentifier),
            'isEmpty' => new Schema\BooleanSchema(),
            'value' => $this->buildFieldValueSchema($fieldTypeIdentifier),
        ];

        return new Schema\ObjectSchema($properties, null, array_keys($properties));
    }

    private function buildFieldValueSchema(string $fieldTypeIdentifier): Schema\ObjectSchema
    {
        $fieldValueSchemaProvider = $this->fieldValueSchemaProviders[$fieldTypeIdentifier] ?? null;

        return $fieldValueSchemaProvider?->provideFieldValueSchema() ?? new Schema\ObjectSchema();
    }
}
