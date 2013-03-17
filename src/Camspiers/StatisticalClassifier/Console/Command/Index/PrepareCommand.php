<?php

namespace Camspiers\StatisticalClassifier\Console\Command\Index;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

class PrepareCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('index:prepare')
            ->setDescription('Prepare an index')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $c = $this->getApplication()->getContainer();
        $c->set(
            'index.index',
            $this->getCachedIndex(
                $input->getArgument('index')
            )
        );
        $c->get('classifier.naive_bayes')->prepareIndex();
    }
}
