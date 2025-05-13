<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Netgen\OpenApiIbexa\Attribute;
use Netgen\OpenApiIbexa\OpenApi\PathProviderInterface;
use Netgen\OpenApiIbexa\OpenApi\SchemaProviderInterface;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Netgen\OpenApiIbexa\Page\PagePartProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

use function file_get_contents;

final class NetgenOpenApiIbexaExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new DelegatingLoader(
            new LoaderResolver(
                [
                    new GlobFileLoader($container, $locator),
                    new YamlFileLoader($container, $locator),
                ],
            ),
        );

        $loader->load('services/**/*.yaml', 'glob');

        $this->processSemanticConfig($container, $config);

        $this->registerAutoConfiguration($container);
        $this->registerAttributeAutoConfiguration($container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configs = [
            'default_settings.yaml' => 'netgen_open_api_ibexa',
        ];

        foreach ($configs as $fileName => $extensionName) {
            $configFile = __DIR__ . '/../Resources/config/' . $fileName;
            $config = Yaml::parse((string) file_get_contents($configFile));
            $container->prependExtensionConfig($extensionName, $config);
            $container->addResource(new FileResource($configFile));
        }
    }

    /**
     * @param array<string, mixed> $config
     */
    private function processSemanticConfig(ContainerBuilder $container, array $config): void
    {
        $processor = new ConfigurationProcessor($container, 'netgen_open_api_ibexa');
        $processor->mapConfigArray('page_schema', $config, ContextualizerInterface::MERGE_FROM_SECOND_LEVEL);
        $processor->mapConfigArray('layouts_dynamic_parameters_schema', $config, ContextualizerInterface::MERGE_FROM_SECOND_LEVEL);
    }

    private function registerAutoConfiguration(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(VisitorInterface::class)
            ->addTag('netgen.openapi_ibexa.page.output.visitor');

        $container
            ->registerForAutoconfiguration(PagePartProviderInterface::class)
            ->addTag('netgen.openapi_ibexa.page.page_part_provider');

        $container
            ->registerForAutoconfiguration(PathProviderInterface::class)
            ->addTag('netgen.openapi_ibexa.path_provider');

        $container
            ->registerForAutoconfiguration(SchemaProviderInterface::class)
            ->addTag('netgen.openapi_ibexa.schema_provider');
    }

    private function registerAttributeAutoConfiguration(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(
            Attribute\AsOutputVisitor::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag('netgen.openapi_ibexa.page.output.visitor');
            },
        );

        $container->registerAttributeForAutoconfiguration(
            Attribute\AsPagePartProvider::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag('netgen.openapi_ibexa.page.page_part_provider');
            },
        );

        $container->registerAttributeForAutoconfiguration(
            Attribute\AsPathProvider::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag('netgen.openapi_ibexa.path_provider');
            },
        );

        $container->registerAttributeForAutoconfiguration(
            Attribute\AsSchemaProvider::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag('netgen.openapi_ibexa.schema_provider');
            },
        );

        $container->registerAttributeForAutoconfiguration(
            Attribute\AsContentTypeSchemaProvider::class,
            static function (ChildDefinition $definition, Attribute\AsContentTypeSchemaProvider $attribute): void {
                $definition->addTag('netgen.openapi_ibexa.ibexa.content_type_schema_provider', ['content_type' => $attribute->contentType]);
            },
        );
    }
}
