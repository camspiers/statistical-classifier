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

use Camspiers\StatisticalClassifier\DataSource\PDO;
use Camspiers\StatisticalClassifier\DataSource\PDOQuery;

use PDO as BasePDO;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PDOCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test:pdo')
            ->setDescription('Test the classifier with a PDO query')
            ->configureIndex()
            ->addArgument(
                'category',
                Input\InputArgument::REQUIRED,
                'Which category this data is'
            )
            ->addArgument(
                'column',
                Input\InputArgument::REQUIRED,
                'Which column to selet'
            )
            ->addArgument(
                'query',
                Input\InputArgument::REQUIRED,
                'The query to run'
            )
            ->addArgument(
                'dsn',
                Input\InputArgument::REQUIRED,
                'The dsn to use'
            )
            ->addArgument(
                'username',
                Input\InputArgument::OPTIONAL,
                'The username to use'
            )
            ->addArgument(
                'password',
                Input\InputArgument::OPTIONAL,
                'The password to use'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $classifier = $this->getClassifier($input);

        $data = new PDO(
            array(
                new PDOQuery(
                    $input->getArgument('category'),
                    new BasePDO(
                        $input->getArgument('dsn'),
                        $input->getArgument('username'),
                        $input->getArgument('password')
                    ),
                    $input->getArgument('query'),
                    $input->getArgument('column')
                )
            )
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
