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

use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Index\IndexInterface;

class TCBD implements TransformInterface
{
    const PARTITION_NAME = 'token_count_by_document';

    public function __construct(
        TokenizerInterface $tokenizer,
        NormalizerInterface $normalizer
    ) {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
    }

    public function apply(IndexInterface $index)
    {
        $data = $index->getDataSource()->getData();
        $transform = array();
        foreach ($data as $category => $documents) {
            $transform[$category] = array();
            foreach ($documents as $document) {
                $transform[$category][] = array_count_values(
                    $this->normalizer->normalize(
                        $this->tokenizer->tokenize(
                            $document
                        )
                    )
                );
            }
        }
        $index->setPartition(self::PARTITION_NAME, $transform);
    }
}
