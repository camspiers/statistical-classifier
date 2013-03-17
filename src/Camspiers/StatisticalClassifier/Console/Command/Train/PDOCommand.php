<?php

/*
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
use Camspiers\StatisticalClassifier\DataSource\PDO;
use Camspiers\StatisticalClassifier\DataSource\PDOQuery;

use PDO as BasePDO;

class PDOCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('train:pdo')
            ->setDescription('Train the classifier with a PDO query')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            )
            ->addArgument(
                'category',
                Input\InputArgument::REQUIRED,
                'Which category this data is'
            )
            ->addArgument(
                'column',
                Input\InputArgument::REQUIRED,
                'Which column to select'
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
        $index = $this->getCachedIndex($input->getArgument('index'));
        $index->setDataSource(
            new Grouped(
                array(
                    $index->getDataSource(),
                    new PDO(
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
                    )
                )
            )
        );
        $index->preserve();
    }
}
