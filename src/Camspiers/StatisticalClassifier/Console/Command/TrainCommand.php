<?php

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrainCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('train')
            ->setDescription('Train the classifies on a data source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
