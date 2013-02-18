<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class Weight implements TransformInterface
{
    const PARTITION_NAME = 'weight';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $data = $index->getPartition($this->dataPartitionName);
        foreach ($data as $category => $tokens) {
            foreach ($tokens as $token => $value) {
                $data[$category][$token] = log($value, 10);
            }
        }
        $index->setPartition(self::PARTITION_NAME, $data);
    }

}
