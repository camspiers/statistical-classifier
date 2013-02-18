<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class DocumentTokenSums implements TransformInterface
{
    const PARTITION_NAME = 'document_token_sums';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $data = $index->getPartition($this->dataPartitionName);
        $transform = array();

        foreach ($data as $category => $documents) {
            $transform[$category] = array();
            foreach ($documents as $docIndex => $document) {
                $transform[$category][$docIndex] = array_sum($document);
            }
        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
