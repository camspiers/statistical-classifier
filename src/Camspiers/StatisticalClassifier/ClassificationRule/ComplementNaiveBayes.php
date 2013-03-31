<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\ClassificationRule;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

/**
 * The classification rule for the NaiveBayes classifier
 *
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class ComplementNaiveBayes implements ClassificationRuleInterface
{
    /**
     * The name of the location of the data in the index
     */
    private $dataPartitionName;
    /**
     * Create the classification rule
     * @param string $dataPartitionName The name of the location of the data in the index
     */
    public function __construct($dataPartitionName)
    {
        $this->dataPartitionName = $dataPartitionName;
    }
    /**
     * Classifies a document against an index
     * @param  IndexInterface $index    The Index to classify against
     * @param  string         $document The document to classify
     * @return string         The category of the document
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
