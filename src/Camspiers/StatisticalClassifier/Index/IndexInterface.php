<?php

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;

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
