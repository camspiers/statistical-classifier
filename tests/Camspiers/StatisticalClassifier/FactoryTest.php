<?php

namespace Camspiers\StatisticalClassifier;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContainerBasic()
    {
        $container = Factory::createContainer();
        $this->assertTrue($container instanceof ContainerBuilder);
    }
}
