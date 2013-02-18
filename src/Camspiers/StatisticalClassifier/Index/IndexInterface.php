<?php

namespace Camspiers\StatisticalClassifier\Index;

interface IndexInterface
{
    public function isPrepared();
    public function setData($data);
    public function getData();
    public function getPartition($partitionName);
    public function setPartition($partitionName, $partitionData, $temporary = true);
    public function removePartition($partitionName);
}
