<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Loader;

use Camspiers\StatisticalClassifier\Config\StatisticalClassifierConfig;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class JsonConfigLoader extends FileLoader
{
    /**
     * @{inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $configFiles = $this->locator->locate($resource, null, false);

        $configs = array();

        foreach ($configFiles as $configFile) {
            $configs[] = json_decode(file_get_contents($configFile), true);
        }

        $processor = new Processor();

        return $processor->processConfiguration(
            new StatisticalClassifierConfig(),
            $configs
        );
    }
    /**
     * @{inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'json' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
