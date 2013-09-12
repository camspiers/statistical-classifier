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

use Camspiers\StatisticalClassifier\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class ClassifyCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('classify')
            ->setDescription('Classify a document')
            ->configureModel()
            ->addArgument(
                'document',
                Input\InputArgument::REQUIRED,
                'The document to classify'
            )
            ->configureClassifier();
    }
    /**
     * Classify a document against an model and an optionally specified classifier
     * @param  Input\InputInterface   $input  The commands input
     * @param  Output\OutputInterface $output The commands output
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $output->writeLn($this->getClassifier($input)->classify($input->getArgument('document')));
    }
}
