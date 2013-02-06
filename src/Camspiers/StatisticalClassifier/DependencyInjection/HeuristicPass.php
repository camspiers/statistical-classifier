<?php

namespace Camspiers\StatisticalClassifier\DependancyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class HeuristicPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
    	
    }
}
