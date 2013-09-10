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
            ->setName('train:pdo')
            ->setDescription('Train the classifier with a PDO query')
            ->configureModel()
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
            )
            ->configureClassifier()
            ->configurePrepare();
    }
    /**
     * Train a classifier with a PDO datasource
     * @param  Input\InputInterface   $input  The commands input
     * @param  Output\OutputInterface $output The commands output
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $modelName = $input->getArgument('model');
        
        $dataSource = $this->getDataSource($modelName);

        $changes = new PDOQuery(
            $input->getArgument('category'),
            new PDO(
                $input->getArgument('dsn'),
                $input->getArgument('username'),
                $input->getArgument('password')
            ),
            $input->getArgument('query'),
            $input->getArgument('column')
        );

        foreach ($changes->getData() as $document) {
            $dataSource->addDocument($document['category'], $document['document']);
        }
        
        $this->cacheDataSource($modelName);
        
        if ($input->getOption('prepare')) {
            $this->getClassifier($input)->prepareModel();
        }
        
        $this->updateSummary(
            $output,
            $changes,
            $dataSource
        );
    }
}
