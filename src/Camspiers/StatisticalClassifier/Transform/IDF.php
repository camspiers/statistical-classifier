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

class IDF implements TransformInterface
{
    const PARTITION_NAME = 'inverse_document_frequency';

    private $dataPartitionName;
    private $documentCountPartitionName;
    private $tokenAppreanceCountPartitionName;

    public function __construct(
        $dataPartitionName,
        $documentCountPartitionName,
        $tokenAppreanceCountPartitionName
    )
    {
        $this->dataPartitionName = $dataPartitionName;
        $this->documentCountPartitionName = $documentCountPartitionName;
        $this->tokenAppreanceCountPartitionName = $tokenAppreanceCountPartitionName;
    }

    public function apply(IndexInterface $index)
    {
        $documentCount = $index->getPartition($this->documentCountPartitionName);
        $tokenAppreanceCount = $index->getPartition($this->tokenAppreanceCountPartitionName);
        $transform = $tokenCountByDocument = $index->getPartition($this->dataPartitionName);
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    $transform
                        [$category]
                        [$documentIndex]
                        [$token] = $count * log(
                            $documentCount / $tokenAppreanceCount[$token],
                            10
                        );
                }
            }
        }
        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
