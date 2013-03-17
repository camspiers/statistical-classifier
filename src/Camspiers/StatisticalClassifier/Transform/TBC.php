<?php

/*
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class TBC implements TransformInterface
{
    const PARTITION_NAME = 'tokens_by_category';

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
            foreach ($documents as $document) {
                foreach (array_keys($document) as $token) {
                    if (!array_key_exists($token, $transform[$category])) {
                        $transform[$category][$token] = true;
                    }
                }
            }
        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
