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

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
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
