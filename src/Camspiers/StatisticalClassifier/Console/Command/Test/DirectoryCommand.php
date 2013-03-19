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

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;

use Camspiers\StatisticalClassifier\DataSource\Directory;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DirectoryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test:directory')
            ->setDescription('Test the classifier against a directory')
            ->configureIndex()
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

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $classifier = $this->getClassifier($input);

        $data = new Directory(
            $input->getArgument('directory'),
            $input->getOption('include')
        );

        $stats = array();
        $fails = array();

        foreach ($data->getData() as $category => $documents) {
            $stats[$category] = 0;
            foreach ($documents as $document) {
                if (($classifiedAs = $classifier->classify($document)) == $category) {
                    $stats[$category]++;
                } else {
                    $fails[] = array($category, $classifiedAs, $document);
                }
            }
            echo $category, ': ', ($stats[$category] / count($documents)), PHP_EOL;
        }
    }
}
