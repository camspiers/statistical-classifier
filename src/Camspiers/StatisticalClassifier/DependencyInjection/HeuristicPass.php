<?php

namespace Camspiers\StatisticalClassifier\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class HeuristicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('heuristic') as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['service'])) {
                    $definition = $container->getDefinition($attributes['service']);
                    $definition->addMethodCall('addHeuristic', array(new Reference($id)));
                }
            }
        }
    }
}
