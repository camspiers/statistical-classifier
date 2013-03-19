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
use Symfony\Component\Console\Output;

use CacheCache\Cache;

use Camspiers\StatisticalClassifier\Index;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Command extends BaseCommand
{
    protected $cache;
    protected $container;
    protected $classifier;

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

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    protected function getCachedIndex($name)
    {
        return new Index\CachedIndex(
            $name,
            $this->cache
        );
    }

    protected function getContainer()
    {
        if (null == $this->container) {
            $this->container = $this->getApplication()->getContainer();
        }
        return $this->container;
    }

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
}
