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

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class ClassifyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('classify')
            ->setDescription('Classify a document')
            ->configureIndex()
            ->addArgument(
                'document',
                Input\InputArgument::REQUIRED,
                'The document to classify'
            )
            ->configureClassifier();
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        echo $this->getClassifier($input)->classify($input->getArgument('document')), PHP_EOL;
    }
}
