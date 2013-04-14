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

use Camspiers\StatisticalClassifier\Config\Config;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Application extends BaseApplication
{
    /**
     * Holds the dependency injection container
     * @var ContainerInterface
     */
    protected $container;
    /**
     * Allows the setting of a container on the appliaction
     * @param ContainerInterface $container The container to set
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }
    /**
     * Returns the set container or returns a newly instantiated one
     * @return ContainerInterface The container
     */
    public function getContainer()
    {
        if (null === $this->container) {
            $containerClass = Config::getOption('container_class');
            $this->container = new $containerClass;
        }

        return $this->container;
    }
}
