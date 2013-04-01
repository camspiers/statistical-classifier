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

use Camspiers\StatisticalClassifier\Config\Config;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class StatisticalClassifierExtension extends BaseExtension
{
    /**
     * Loads the packages services from a yaml file
     * @param  array            $config    The config the extension is loaded with
     * @param  ContainerBuilder $container The container to add the services to
     * @return null
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../../../config'));
        $loader->load($this->getAlias() . '_services.yml');
        $container->setParameter('classifier_path', Config::getClassifierPath());
        $container->setParameter('run_path', Config::getRunPath());
    }
}
