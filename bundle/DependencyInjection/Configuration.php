<?php

declare(strict_types=1);

namespace Netgen\Bundle\IbexaOpenApiBundle\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netgen_ibexa_open_api');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->generateScopeBaseNode($rootNode)
            ->arrayNode('page_schema')
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('identifier')
                ->arrayPrototype()
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('reference_name')->end()
                        ->booleanNode('required')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
