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

use CacheCache\Cache;
use Camspiers\StatisticalClassifier\Classifier\Classifier;
use Camspiers\StatisticalClassifier\Config\Config;
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Model\CachedModel;
use Camspiers\StatisticalClassifier\Model\SVMCachedModel;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Command extends BaseCommand
{
    /**
     * Holds the config from the config.json files
     * @var array
     */
    protected $config;
    /**
     * Holds the CacheCache\Cache instance
     * @var Cache
     */
    protected $cache;
    /**
     * Holds the container instance for caching
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    /**
     * Holds the classifier instance for caching
     * @var Classifier
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
                'complement_naive_bayes'
            );

        return $this;
    }
    /**
     * Adds arguments required for using a specified model in a command
     * @return Command This command to allow chaining
     */
    protected function configureModel()
    {
        $this
            ->addArgument(
                'model',
                Input\InputArgument::REQUIRED,
                'Name of model'
            );

        return $this;
    }
    /**
     * Adds options to allow automatically prepare the model
     * @return Command This command to allow chaining
     */
    protected function configurePrepare()
    {
        $this
            ->addOption(
                'prepare',
                'p',
                Input\InputOption::VALUE_NONE,
                'Prepare the model after training'
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
     * Get an CachedModel based off a model name and the Cache instance
     * @param  string $name The name of the model
     * @param bool    $svm
     * @return CachedModel The cached model
     */
    protected function getModel($name, $svm = false)
    {
        static $models = array();
        
        if (!isset($models[$name])) {
            if ($svm) {
                $models[$name] = new SVMCachedModel(
                    sprintf(
                        "%s/%s.svm",
                        rtrim($this->getContainer()->getParameter('cache.path'), '/'),
                        $name
                    ),
                    $this->cache
                );
            } else {
                $models[$name] = new CachedModel(
                    $name.'.model',
                    $this->cache
                );
            }
        }
        
        return $models[$name];
    }
    /**
     * @param $name
     * @return DataArray
     */
    protected function getDataSource($name)
    {
        static $datasource = array();
        
        if (!isset($datasource[$name])) {
            $datasource[$name] = $this->cache->get($name.'.source') ?: new DataArray();
        }
        
        return $datasource[$name];
    }
    /**
     * @param $name
     */
    protected function cacheDataSource($name)
    {
        $this->cache->set(
            $name.'.source',
            $this->getDataSource($name)
        );
    }
    /**
     * Return the dependency injection container fetching it off the app if it doesn't exist
     * @return Symfony\Component\DependencyInjection\ContainerInterface The container
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $this->container = $this->getApplication()->getContainer();
        }

        return $this->container;
    }
    /**
     * Returns a classifier based of the commands input and the specified model (if exists)
     * @param  Input\InputInterface $input The commands input
     * @throws \RuntimeException
     * @return Classifier           The built classifier
     */
    protected function getClassifier(Input\InputInterface $input)
    {
        if (null === $this->classifier) {
            $container = $this->getContainer();
            $classifier = 'classifier.' . $input->getOption('classifier');
            if ($container->has($classifier)) {
                $modelName = $input->getArgument('model');
                
                $container->set(
                    'classifier.model',
                    $this->getModel($modelName, $classifier == 'classifier.svm')
                );

                $container->set(
                    'classifier.source',
                    $this->getDataSource($modelName)
                );
                
                $this->classifier = $container->get($classifier);
            } else {
                throw new \RuntimeException("Classifier '$classifier' doesn't exist");
            }
        }

        return $this->classifier;
    }
}
