<?php

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClassifyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('classify')
            ->setDescription('Classify a document');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
