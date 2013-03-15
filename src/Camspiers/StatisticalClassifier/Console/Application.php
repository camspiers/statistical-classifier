<?php

namespace Camspiers\StatisticalClassifier\Console;

use Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends BaseApplication
{
    protected $container;
    
    public function __construct()
    {
        parent::__construct('Statistical Classifier', '0.2.0');
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer()
    {
        if (null == $this->container) {
            $this->container = new StatisticalClassifierServiceContainer();
        }
        return $this->container;
    }
}
