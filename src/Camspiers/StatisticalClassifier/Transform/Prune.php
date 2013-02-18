<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class Prune implements TransformInterface
{
    private $exclude = array();

    public function __construct(array $exclude = null)
    {
        if (is_array($exclude)) {
            $this->exclude = $exclude;
        }
    }

    public function apply(IndexInterface $index)
    {
        $partitions = $index->getPartitions();
        foreach ($partitions as $partition) {
            if (!in_array($partition, $this->exclude)) {
                $index->removePartition($partition);
            }
        }
    }
}
