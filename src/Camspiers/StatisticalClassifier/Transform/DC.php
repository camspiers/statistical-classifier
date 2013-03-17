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
