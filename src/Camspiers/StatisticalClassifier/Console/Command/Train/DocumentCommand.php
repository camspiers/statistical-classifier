<?php

namespace Camspiers\StatisticalClassifier\Console\Command\Train;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

class DocumentCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('train:document')
            ->setDescription('Train the classifier with a document')
            ->addArgument(
                'category',
                Input\InputArgument::REQUIRED,
                'Which category this data is'
            )
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            )
            ->addArgument(
                'document',
                Input\InputArgument::REQUIRED,
                'The document to train on'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {

    }
}
