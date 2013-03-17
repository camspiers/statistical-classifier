<?php

/*
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\ClassificationRule;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

class NaiveBayes implements ClassificationRuleInterface
{
    private $dataPartitionName;

    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }

    /**
     * {@inheritDoc}
     */
    public function classify(IndexInterface $index, $document)
    {
        $results = array();
        $tokens = array_count_values($document);
        $weights = $index->getPartition($this->dataPartitionName);
        foreach (array_keys($weights) as $category) {
            $results[$category] = 0;
            foreach ($tokens as $token => $count) {
                if (array_key_exists($token, $weights[$category])) {
                    $results[$category] += $count * $weights[$category][$token];
                }
            }
        }
        asort($results, SORT_NUMERIC);
        return key($results);
    }
}
