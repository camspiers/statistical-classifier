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
