<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class CommandCompilerPass implements CompilerPassInterface
{
    /**
     * Finds services tagged with console.command and adds them to the console application
     * @param  ContainerBuilder $container The container with the relevant definitions
     * @return null
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('console.application')) {
            $application = $container->getDefinition('console.application');
            foreach ($container->findTaggedServiceIds('console.command') as $id => $tags) {
                foreach ($tags as $tag) {
                    $application->addMethodCall(
                        'add',
                        array(
                            new Reference($id)
                        )
                    );
                    if (isset($tag['cache']) && $tag['cache']) {
                        $container->getDefinition($id)->addMethodCall(
                            'setCache',
                            array(
                                new Reference('cache')
                            )
                        );
                    }
                }
            }
        }
    }
}
