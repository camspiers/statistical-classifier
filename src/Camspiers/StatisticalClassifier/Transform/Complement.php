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

class Complement implements TransformInterface
{
    const PARTITION_NAME = 'complement';

    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $data = $index->getPartition($this->dataPartitionName);
        $tokensByCategory = $index->getPartition(TBC::PARTITION_NAME);
        $documentTokenSums = $index->getPartition(DocumentTokenSums::PARTITION_NAME);
        $documentTokenCounts = $index->getPartition(DocumentTokenCounts::PARTITION_NAME);
        $categories = array_keys($tokensByCategory);
        $transform = array();
        $numeratorCache = array();

        foreach ($tokensByCategory as $category => $tokens) {

            $tokens = array_keys($tokens);
            $transform[$category] = array();
            $categoriesSelection = array_diff($categories, array($category));

            $denominators = array();

            foreach ($categoriesSelection as $category2) {
                $denominators[$category2] = array_sum($documentTokenSums[$category2]) + array_sum($documentTokenCounts[$category2]);
            }

            foreach ($tokens as $token) {

                $numerator = 0;
                $denominator = 0;
                foreach ($categoriesSelection as $category2) {
                    $numerator += count($data[$category2]);
                    $denominator += $denominators[$category2];

                    if (!isset($numeratorCache[$category2])) {
                        $numeratorCache[$category2] = array();
                    }

                    if (!isset($numeratorCache[$category2][$token])) {

                        $numeratorCache[$category2][$token] = 0;

                        foreach ($data[$category2] as $docIndex => $document) {
                            if (array_key_exists($token, $document)) {
                                $numeratorCache[$category2][$token] += $document[$token];
                            }
                        }

                    }

                    $numerator += $numeratorCache[$category2][$token];

                }

                $transform[$category][$token] = $numerator / $denominator;

            }
        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
