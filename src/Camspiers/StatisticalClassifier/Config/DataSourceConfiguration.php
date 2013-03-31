<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DataSourceConfiguration implements ConfigurationInterface
{
    /**
     * Returns a specification for data sources
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('datasource');

        $rootNode
            ->prototype('array')
            ->children()
                ->scalarNode('category')->isRequired()->end()
                ->scalarNode('document')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
