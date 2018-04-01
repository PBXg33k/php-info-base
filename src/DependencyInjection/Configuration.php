<?php
namespace Pbxg33k\InfoBase\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        throw new InvalidConfigurationException("You must override getConfigTreeBuilder and call buildConfigTreeBuilder");
    }

    protected function buildConfigTreeBuilder(TreeBuilder $treeBuilder, $rootName)
    {
        $rootNode = $treeBuilder->root($rootName);

        // @codingStandardsIgnoreStart
        $rootNode
            ->children()
                ->booleanNode('init_services')->defaultTrue()->end()
                ->arrayNode('services')->end()
                ->arrayNode('preferred_order')->end()
                ->arrayNode('service_weight')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('service')->end()
                            ->floatNode('weight')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('guzzle')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('proxy')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        // @codingStandardsIgnoreEnd
        return $treeBuilder;
    }
}