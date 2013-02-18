<?php

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
        $tokenCountByDocument = $index->getPartition(TCBD::PARTITION_NAME);
        $categories = array_keys($tokenCountByDocument);

        //Tokens by category
        $tokensByCategory = array();
        foreach ($tokenCountByDocument as $category => $documents) {
            $tokensByCategory[$category] = array();
            foreach ($documents as $document) {
                foreach (array_keys($document) as $token) {
                    if (!array_key_exists($token, $tokensByCategory[$category])) {
                        $tokensByCategory[$category][$token] = true;
                    }
                }
            }
        }

        unset($tokenCountByDocument);

        $transform = array();

        foreach ($tokensByCategory as $category => $tokens) {
            $transform[$category] = array();
            foreach (array_keys($tokens) as $token) {
                // transform [cat] [token]
                // look at every doc not in the current category
                $numerator = 0;
                $denominator = 0;
                foreach ($categories as $category2) {
                    if ($category !== $category2) {
                        foreach ($data[$category2] as $document) {
                            $numerator++;
                            if (isset($document[$token])) {
                                $numerator += $document[$token];
                            }
                            $denominator += array_sum($document) + count($document);
                        }
                    }
                }
                $transform[$category][$token] = $numerator / $denominator;
            }
        }

        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
