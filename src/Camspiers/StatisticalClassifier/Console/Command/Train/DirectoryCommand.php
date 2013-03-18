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

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

use Camspiers\StatisticalClassifier\DataSource\Grouped;
use Camspiers\StatisticalClassifier\DataSource\Directory;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DirectoryCommand extends CacheableCommand
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
            )
            ->addOption(
                'include',
                'i',
                Input\InputOption::VALUE_OPTIONAL | Input\InputOption::VALUE_IS_ARRAY,
                'The categories from the directory to include'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $index = $this->getCachedIndex($input->getArgument('index'));
        $index->setDataSource(
            new Grouped(
                array(
                    $index->getDataSource(),
                    new Directory(
                        $input->getArgument('directory'),
                        $input->getOption('include')
                    )
                )
            )
        );
        $index->preserve();
    }
}
