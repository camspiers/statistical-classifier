<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
interface IndexInterface
{
    public function isPrepared();
    public function setDataSource(DataSourceInterface $dataSource);
    public function getDataSource();
    public function getPartition($partitionName);
    public function setPartition($partitionName, $partitionData);
    public function removePartition($partitionName);
    public function getPartitions();
}
