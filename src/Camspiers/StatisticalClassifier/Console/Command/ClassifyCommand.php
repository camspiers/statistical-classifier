<?php

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

class ClassifyCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('classify')
            ->setDescription('Classify a document')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            )
            ->addArgument(
                'document',
                Input\InputArgument::REQUIRED,
                'The document to classify'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $c = $this->getApplication()->getContainer();
        $c->set(
            'index.index',
            $index = $this->getCachedIndex(
                $input->getArgument('index')
            )
        );
        die($c->get('classifier.naive_bayes')->classify($input->getArgument('document')));
    }
}
