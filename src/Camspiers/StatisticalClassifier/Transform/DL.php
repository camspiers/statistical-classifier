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
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DL implements TransformInterface
{
    const PARTITION_NAME = 'document_length';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $transform = $tokenCountByDocument = $index->getPartition($this->dataPartitionName);
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                $denominator = 0;
                foreach ($document as $count) {
                    $denominator += $count * $count;
                }
                $denominator = sqrt($denominator);
                foreach ($document as $token => $count) {
                    $transform
                    [$category]
                    [$documentIndex]
                    [$token] = $count / $denominator;
                }
            }
        }
        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
