<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console;

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
