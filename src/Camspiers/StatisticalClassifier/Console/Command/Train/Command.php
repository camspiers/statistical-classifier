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

use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command as BaseCommand;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;

/**
 * @author Cam Spiers <camspiers@gmail.com>
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
        $changesCategoriesTotal = count($changes->getCategories());
        $changesDocumentsTotal = count($changes->getData(), COUNT_RECURSIVE) - $changesCategoriesTotal;
        $categoriesTotal = count($current->getCategories());
        $documentsTotal = count($current->getData(), COUNT_RECURSIVE) - $categoriesTotal;
        $output->writeLn(
            array(
                "Added $changesCategoriesTotal documents from $changesDocumentsTotal categories",
                "Index now contains $documentsTotal documents in $categoriesTotal categories"
            )
        );
    }
}

