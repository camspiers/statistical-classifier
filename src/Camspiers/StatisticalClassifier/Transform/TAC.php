<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class TAC implements TransformInterface
{
    const PARTITION_NAME = 'token_appearance_count';

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
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    if ($count > 0) {
                        if (!array_key_exists($token, $transform)) {
                            $transform[$token] = 0;
                        }
                        $transform[$token]++;
                    }
                }
            }
        }
        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
