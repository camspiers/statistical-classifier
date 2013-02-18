<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class TF implements TransformInterface
{
    const PARTITION_NAME = 'term_frequency';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $transform = $index->getPartition($this->dataPartitionName);
        foreach ($transform as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    $transform
                        [$category]
                        [$documentIndex]
                        [$token] = log($count + 1, 10);
                }
            }
        }
        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
