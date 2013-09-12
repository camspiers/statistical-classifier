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

use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DocumentCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('train:document')
            ->setDescription('Train the classifier with a document')
            ->configureModel()
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
    /**
     * Train a classifier with a single document
     * @param Input\InputInterface $input
     * @return \Camspiers\StatisticalClassifier\DataSource\DataArray|Directory
     */
    protected function getChanges(Input\InputInterface $input)
    {
        return new DataArray(
            array(
                'category' => $input->getArgument('category'),
                'document' => $input->getArgument('document')
            )
        );
    }
}
