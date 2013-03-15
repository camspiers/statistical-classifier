<?php

namespace Camspiers\StatisticalClassifier\Console\Command\Index;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

class RemoveCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('index:remove')
            ->setDescription('')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $this->cache->delete($input->getArgument('index'));
    }
}
