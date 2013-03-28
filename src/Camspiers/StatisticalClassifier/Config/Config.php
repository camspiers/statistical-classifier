<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Config;

use Symfony\Component\Config\FileLocator;
use Camspiers\StatisticalClassifier\Loader\JsonConfigLoader;

use RuntimeException;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Config
{
    /**
     * The config cache
     * @var array
     */
    private static $config;
    /**
     * Returns the config which is a combination of the default and the global
     * @return array The configuration
     */
    public static function getConfig()
    {
        if (null === self::$config) {
            $loader = new JsonConfigLoader(
                new FileLocator(
                    array(
                        'config',
                        $_SERVER['HOME'] . '/.classifier',
                        '/usr/local/.classifier'
                    )
                )
            );
            self::$config = $loader->load('config.json');
        }

        return self::$config;
    }
    public static function getConfigOption($parameter)
    {
        $config = self::getConfig();
        if (array_key_exists($parameter, $config)) {
            return $config[$parameter];
        } else {
            throw new RuntimeException("Config parameter '$parameter' doesn't exist");
        }
    }
}
