<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\OpenApi\Model\Schema\ObjectSchema;
use Netgen\OpenApi\Model\Schema\ReferenceSchema;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;

use function count;

final class PageSchemaProvider implements SchemaProviderInterface
{
    public function __construct(
        private ConfigResolverInterface $configResolver,
    ) {}

    public function provideSchemas(): iterable
    {
        /** @var array<string, array{reference_name: string, required: bool, enabled: bool}> $pageSchemaConfig */
        $pageSchemaConfig = $this->configResolver->getParameter('page_schema', 'netgen_open_api_ibexa');

        $properties = [];
        $requiredProperties = [];

        foreach ($pageSchemaConfig as $identifier => $schemaConfig) {
            if ($schemaConfig['enabled']) {
                $properties[$identifier] = new ReferenceSchema($schemaConfig['reference_name']);

                if ($schemaConfig['required']) {
                    $requiredProperties[] = $identifier;
                }
            }
        }

        yield 'Page' => new ObjectSchema($properties, null, count($requiredProperties) > 0 ? $requiredProperties : null);
    }
}
