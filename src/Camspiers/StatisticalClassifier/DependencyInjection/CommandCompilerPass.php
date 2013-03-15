<?php

namespace Camspiers\StatisticalClassifier\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class CommandCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $application = $container->getDefinition('console.application');
        foreach ($container->findTaggedServiceIds('console.command') as $id => $tags) {
            foreach ($tags as $tag) {
                $application->addMethodCall(
                    'add',
                    array(
                        new Reference($id)
                    )
                );
            }
        }
    }
}
