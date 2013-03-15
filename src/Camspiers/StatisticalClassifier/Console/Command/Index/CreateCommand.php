<?php

namespace Camspiers\StatisticalClassifier\Console\Command\Index;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use CacheCache\Cache;

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

class CreateCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('index:create')
            ->setDescription('')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $this->getCachedIndex($input->getArgument('index'))->preserve();
    }
}
