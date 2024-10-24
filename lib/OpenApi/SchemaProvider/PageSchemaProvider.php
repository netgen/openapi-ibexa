<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\SchemaProvider;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ObjectSchema;
use Netgen\IbexaOpenApi\OpenApi\Model\Schema\ReferenceSchema;
use Netgen\IbexaOpenApi\OpenApi\SchemaProviderInterface;

use function count;

final class PageSchemaProvider implements SchemaProviderInterface
{
    public function __construct(
        private ConfigResolverInterface $configResolver,
    ) {}

    public function provideSchemas(): iterable
    {
        /** @var array<string, array{reference_name: string, required: bool, enabled: bool}> $pageSchemaConfig */
        $pageSchemaConfig = $this->configResolver->getParameter('page_schema', 'netgen_ibexa_open_api');

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
