<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input;
use Symfony\Component\Config\FileLocator;

use CacheCache\Cache;

use Camspiers\StatisticalClassifier\Index;
use Camspiers\StatisticalClassifier\Loader\JsonConfigLoader;
use Camspiers\StatisticalClassifier\Classifier\ClassifierInterface;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Command extends BaseCommand
{
    protected $config;
    /**
     * Holds the CacheCache\Cache instance
     * @var CacheCache\Cache
     */
    protected $cache;
    /**
     * Holds the container instance for caching
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    /**
     * Holds the classifier instance for caching
     * @var ClassifierInterface
     */
    protected $classifier;
    /**
     * Adds options nessacary for calling getClassifier in a command
     * @return Command This command to allow chaining
     */
    protected function configureClassifier()
    {
        $this
            ->addOption(
                'classifier',
                'c',
                Input\InputOption::VALUE_OPTIONAL,
                'Name of classifier',
                'classifier.naive_bayes'
            );

        return $this;
    }
    /**
     * Adds arguments required for using a specified index in a command
     * @return Command This command to allow chaining
     */
    protected function configureIndex()
    {
        $this
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            );

        return $this;
    }
    /**
     * Adds options to allow automatically prepare the index
     * @return Command This command to allow chaining
     */
    protected function configurePrepare()
    {
        $this
            ->addOption(
                'prepare',
                'p',
                Input\InputOption::VALUE_NONE,
                'Prepare the index after training'
            );

        return $this;
    }
    /**
     * Allow for cache to be stored on command for setter injection
     * @param Cache $cache The cache to store
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
    /**
     * Get an CachedIndex based off a index name and the Cache instance
     * @param  string            $name The name of the index
     * @return Index\CachedIndex The cached index
     */
    protected function getCachedIndex($name)
    {
        return new Index\CachedIndex(
            $name,
            $this->cache
        );
    }
    /**
     * Return the dependency injection container fetching it off the app if it doesn't exist
     * @return Symfony\Component\DependencyInjection\ContainerInterface The container
     */
    protected function getContainer()
    {
        if (null == $this->container) {
            $this->container = $this->getApplication()->getContainer();
        }

        return $this->container;
    }
    /**
     * Returns a classifier based of the commands input and the specified index (if exists)
     * @param  Input\InputInterface $input The commands input
     * @param  Index\IndexInterface $index Optional index to use in the classifier
     * @return ClassifierInterface  The built classifier
     */
    protected function getClassifier(Input\InputInterface $input, Index\Index $index = null)
    {
        if (null === $this->classifier) {
            $container = $this->getContainer();
            if (null == $index) {
                $index = $this->getCachedIndex(
                    $input->getArgument('index')
                );
            }
            $container->set(
                'index.index',
                $index
            );
            $this->classifier = $container->get($input->getOption('classifier'));
        }

        return $this->classifier;
    }
    /**
     * Returns the config which is a combination of the default and the global
     * @return array The configuration
     */
    protected function getConfig()
    {
        if (null == $this->config) {
            $loader = new JsonConfigLoader(
                new FileLocator(
                    array(
                        'config',
                        $_SERVER['HOME'] . '/.classifier',
                        '/usr/local/.classifier'
                    )
                )
            );
            $this->config = $loader->load('config.json');
        }

        return $this->config;
    }
}
