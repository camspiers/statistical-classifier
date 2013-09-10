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

use Camspiers\StatisticalClassifier\DataSource\PDOQuery;
use PDO;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PDOCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('test:pdo')
            ->setDescription('Test the classifier with a PDO query')
            ->configureModel()
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
    /**
     * Test a PDO data source
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $this->test(
            $output,
            $this->getClassifier($input),
            new PDOQuery(
                $input->getArgument('category'),
                new PDO(
                    $input->getArgument('dsn'),
                    $input->getArgument('username'),
                    $input->getArgument('password')
                ),
                $input->getArgument('query'),
                $input->getArgument('column')
            )
        );
    }
}
