<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class WeightNormalization implements TransformInterface
{
    const PARTITION_NAME = 'weight_normalization';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $data = $index->getPartition($this->dataPartitionName);

        $transform = array();

        foreach ($data as $category => $tokens) {
            $transform[$category] = array();
            $sum = array_sum($tokens);
            foreach ($tokens as $token => $weight) {
                $transform[$category][$token] = $weight / $sum;
            }
        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
