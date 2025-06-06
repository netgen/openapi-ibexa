<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netgen_open_api_ibexa');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->generateScopeBaseNode($rootNode)
            ->arrayNode('page_schema')
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('identifier')
                ->arrayPrototype()
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('reference_name')
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()
                        ->booleanNode('required')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('layouts_dynamic_parameters_schema')
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('identifier')
                ->arrayPrototype()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('parameter')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('reference_name')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
