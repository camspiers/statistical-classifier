<?php

namespace Camspiers\StatisticalClassifier\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DataSourceConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('datasource');

        // $rootNode
        //     ->children()
        //         ->arrayNode('categories')
        //             ->prototype('array')
        //             ->children()
        //                 ->scalarNode()->end()
        //             ->end()
        //         ->end()
        //     ->end();
        return $treeBuilder;
    }
}
