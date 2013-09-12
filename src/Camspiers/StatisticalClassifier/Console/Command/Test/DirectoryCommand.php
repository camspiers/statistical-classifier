<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command\Test;

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DirectoryCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('test:directory')
            ->setDescription('Test the classifier against a directory')
            ->configureModel()
            ->addArgument(
                'directory',
                Input\InputArgument::REQUIRED,
                'The directory to test against'
            )
            ->addOption(
                'include',
                'i',
                Input\InputOption::VALUE_OPTIONAL | Input\InputOption::VALUE_IS_ARRAY,
                'The categories from the directory to include'
            )
            ->configureClassifier()
            ->configurePrepare();
    }
    /**
     * Test a directory data source
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $this->test(
            $output,
            $this->getClassifier($input),
            new Directory(
                array(
                    'directory' => $input->getArgument('directory'),
                    'include' => $input->getOption('include')
                )
            )
        );
    }
}
