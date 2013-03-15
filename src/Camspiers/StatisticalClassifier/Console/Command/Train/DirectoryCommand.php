<?php

namespace Camspiers\StatisticalClassifier\Console\Command\Train;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

class DirectoryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('train:directory')
            ->setDescription('Train the classifier with a directory')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            )
            ->addArgument(
                'directory',
                Input\InputArgument::REQUIRED,
                'The directory to train on'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {

    }
}
