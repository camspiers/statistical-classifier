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
        $tokByCat = $index->getPartition(TBC::PARTITION_NAME);
        $docCount = $index->getPartition(DC::PARTITION_NAME);
        $docTokenCounts = $index->getPartition(DocumentTokenCounts::PARTITION_NAME);
        $cats = array_keys($tokByCat);
        $trans = array();

        $tokByCatSums = array();

        foreach ($tokByCat as $cat => $tokens) {
            $tokByCatSums[$cat] = array_sum($tokens);
        }

        $documentCounts = array();

        foreach ($data as $cat => $documents) {
            $documentCounts[$cat] = count($documents);
        }

        foreach ($tokByCat as $cat => $tokens) {

            $trans[$cat] = array();
            $categoriesSelection = array_diff($cats, array($cat));

            $docsInOtherCats = $docCount - $documentCounts[$cat];

            foreach (array_keys($tokens) as $token) {
                $trans[$cat][$token] = $docsInOtherCats;
                foreach ($categoriesSelection as $currCat) {
                    if (array_key_exists($token, $tokByCat[$currCat])) {
                        $trans[$cat][$token] += $tokByCat[$currCat][$token];
                    }
                }
                foreach ($categoriesSelection as $currCat) {
                    $trans[$cat][$token] = $trans[$cat][$token] / ($tokByCatSums[$currCat] + $docTokenCounts[$currCat]);
                }

            }

        }

        $index->setPartition(self::PARTITION_NAME, $trans);
    }
}
