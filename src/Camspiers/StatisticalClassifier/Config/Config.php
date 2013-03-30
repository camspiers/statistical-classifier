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
     * @internal param array $paths
     * @return array The configuration
     */
    public static function getConfig()
    {
        if (null === self::$config) {
            $configs = array();
            $configs[] = CLASSIFIER_PATH . '/config';
            if (realpath(CLASSIFIER_PATH . '/config') !== realpath(__DIR__ . '/../../../../config')) {
                $configs[] = __DIR__ . '/../../../../config';
            }
            $configs[] = $_SERVER['HOME'] . '/.classifier';
            $configs[] = '/usr/local/.classifier';
            $loader = new JsonConfigLoader(
                new FileLocator(
                    $configs
                )
            );
            self::$config = $loader->load('config.json');
        }

        return self::$config;
    }
    /**
     * @param $option
     * @throws \RuntimeException
     * @internal param string $parameter
     * @return mixed
     */
    public static function getOption($option)
    {
        $config = self::getConfig();
        if (array_key_exists($option, $config)) {
            return $config[$option];
        } else {
            throw new RuntimeException("Config option '$option' doesn't exist");
        }
    }
    public static function getOptionPath($option)
    {
        return self::getPath(self::getOption($option));
    }

    public static function getPath($path)
    {
        if ('/' === $path[0]) {
            return $path;
        } else {
            return CLASSIFIER_PATH . rtrim($path, '/');
        }
    }

    public static function getPathFromClass($class)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $class);
    }
}
