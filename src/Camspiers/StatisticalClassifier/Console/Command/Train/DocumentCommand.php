<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command\Train;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DocumentCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('train:document')
            ->setDescription('Train the classifier with a document')
            ->configureIndex()
            ->addArgument(
                'category',
                Input\InputArgument::REQUIRED,
                'Which category this data is'
            )
            ->addArgument(
                'document',
                Input\InputArgument::REQUIRED,
                'The document to train on'
            )
            ->configureClassifier()
            ->configurePrepare();
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $index = $this->getCachedIndex($input->getArgument('index'));
        $index->getDataSource()->addDocument(
            $input->getArgument('category'),
            $input->getArgument('document')
        );
        $index->preserve();
        if ($input->getOption('prepare')) {
            $this->getClassifier($input, $index)->prepareIndex();
        }
    }
}
