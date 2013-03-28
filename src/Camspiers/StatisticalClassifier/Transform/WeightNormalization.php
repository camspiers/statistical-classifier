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
