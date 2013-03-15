<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class DC implements TransformInterface
{
    const PARTITION_NAME = 'document_count';

    public function apply(IndexInterface $index)
    {
        $count = 0;
        $data = $index->getDataSource()->getData();
        foreach ($data as $documents) {
            $count += count($documents);
        }
        $index->setPartition(self::PARTITION_NAME, $count);
    }
}
