<?php


namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;
use Camspiers\StatisticalClassifier\Transform\DocumentTokenSums;

/**
 * Class SVM
 * @package Camspiers\StatisticalClassifier\Transform
 */
class SVM implements TransformInterface
{
    /**
     *
     */
    const MODEL_PARTITION_NAME = 'svm_model';
    /**
     *
     */
    const CATEGORY_MAP_PARITITION_NAME = 'svm_category_map';
    /**
     *
     */
    const TOKEN_MAP_PARITITION_NAME = 'svm_token_map';
    /**
     * @var \SVM
     */
    private $svm;
    /**
     * @var
     */
    private $dataPartitionName;
    /**
     * @param \SVM $svm
     * @param      $dataPartitionName
     */
    public function __construct(\SVM $svm, $dataPartitionName)
    {
        $this->svm = $svm;
        $this->dataPartitionName = $dataPartitionName;
    }
    /**
     * @param IndexInterface $index
     */
    public function apply(IndexInterface $index)
    {
        $data = $index->getPartition($this->dataPartitionName);
        $transform = array();
        $categoryMap = array();
        $categoryCount = 0;
        $tokenMap = array();
        $tokenCount = 1;

        // Produce the token and category maps for the whole document set
        foreach ($data as $category => $documents) {
            if (!array_key_exists($category, $categoryMap)) {
                $categoryMap[$category] = $categoryCount;
                $categoryCount++;
            }
            foreach ($documents as $document) {
                foreach (array_keys($document) as $token) {
                    if (!array_key_exists($token, $tokenMap)) {
                        $tokenMap[$token] = $tokenCount;
                        $tokenCount++;
                    }
                }
            }
        }

        // Prep the svm data set for use
        foreach ($data as $category => $documents) {
            foreach ($documents as $document) {
                $entry = array(
                    $categoryMap[$category]
                );
                foreach ($document as $token => $value) {
                    $entry[$tokenMap[$token]] = $value;
                }
                ksort($entry, SORT_NUMERIC);
                $transform[] = $entry;
            }
        }

        // Weight the data set by the number of docs that appear in each class.
        $weights = array();

        foreach ($data as $category => $documents) {
            $weights[$categoryMap[$category]] = count($documents);
        }

        $lowest = min($weights);

        $weights = array_map(
            function ($weight) use ($lowest) {
                return $lowest / $weight;
            },
            $weights
        );

        $index->setPartition(self::CATEGORY_MAP_PARITITION_NAME, array_flip($categoryMap));
        $index->setPartition(self::TOKEN_MAP_PARITITION_NAME, $tokenMap);
        $index->setPartition(self::MODEL_PARTITION_NAME, $this->svm->train($transform, $weights));
    }
}