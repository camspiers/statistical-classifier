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

use Camspiers\StatisticalClassifier\Console\Command\Command as BaseCommand;
use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Command extends BaseCommand
{
    /**
     * Outputs a summary of what has changes in the index
     * @param  Output\OutputInterface $output  The output object
     * @param  DataSourceInterface    $changes The data source with changes
     * @param  DataSourceInterface    $current The grouped data source
     * @return null
     */
    protected function updateSummary(
        Output\OutputInterface $output,
        DataSourceInterface $changes,
        DataSourceInterface $current
    ) {
        $changesCatTotal = count($changes->getCategories());
        $changesDocTotal = count($changes->getData());
        $categoriesTotal = count($current->getCategories());
        $documentsTotal = count($current->getData());
        $output->writeLn(
            array(
                "Added $changesDocTotal documents from $changesCatTotal categories",
                "Index now contains $documentsTotal documents in $categoriesTotal categories"
            )
        );
    }
}
