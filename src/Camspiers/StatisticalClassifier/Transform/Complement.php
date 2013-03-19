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
        $documentCount = $index->getPartition(DC::PARTITION_NAME);
        $documentTokenCounts = $index->getPartition(DocumentTokenCounts::PARTITION_NAME);
        $categories = array_keys($tokensByCategory);
        $transform = array();
        $numeratorCache = array();

        $tokensByCategorySums = array();

        foreach ($tokensByCategory as $category => $tokens) {
            $tokensByCategorySums[$category] = array_sum($tokens);
        }

        $documentCounts = array();

        foreach ($data as $category => $documents) {
            $documentCounts[$category] = count($documents);
        }

        foreach ($tokensByCategory as $category => $tokens) {

            $transform[$category] = array();
            $categoriesSelection = array_diff($categories, array($category));

            $docsInOtherCategories = $documentCount - $documentCounts[$category];

            foreach ($tokens as $token => $count) {
                $transform[$category][$token] = $docsInOtherCategories;
                foreach ($categoriesSelection as $selectedCategory) {
                    if (array_key_exists($token, $tokensByCategory[$selectedCategory])) {
                        $transform[$category][$token] += $tokensByCategory[$selectedCategory][$token];
                    }
                }

                foreach ($categoriesSelection as $selectedCategory) {
                    $transform[$category][$token] = $transform[$category][$token] / ($tokensByCategorySums[$selectedCategory] + $documentTokenCounts[$selectedCategory]);
                }

            }

        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
