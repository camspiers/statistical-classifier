<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\ClassificationRule;
use Camspiers\StatisticalClassifier\Index\IndexInterface;
use Camspiers\StatisticalClassifier\Normalizer\Lowercase;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\Word;
use Camspiers\StatisticalClassifier\Transform;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class SVM extends GenericClassifier
{
    /**
     * @param IndexInterface      $index
     * @param TokenizerInterface  $tokenizer
     * @param NormalizerInterface $normalizer
     * @param \SVM                $svm
     */
    public function __construct(
        IndexInterface $index,
        TokenizerInterface $tokenizer = null,
        NormalizerInterface $normalizer = null,
        \SVM $svm = null
    ) {
        $tokenizer = $tokenizer ?: new Word();
        $normalizer = $normalizer ?: new Lowercase();
        $svm = $svm ?: new \SVM();
        parent::__construct(
            $index,
            new ClassificationRule\SVM(),
            $tokenizer,
            $normalizer,
            array(
                new Transform\DC(),
                new Transform\TCBD(
                    $tokenizer,
                    $normalizer
                ),
                new Transform\TAC(
                    Transform\TCBD::PARTITION_NAME
                ),
                new Transform\TFIDF(
                    Transform\TCBD::PARTITION_NAME,
                    Transform\DC::PARTITION_NAME,
                    Transform\TAC::PARTITION_NAME
                ),
                new Transform\DL(
                    Transform\TFIDF::PARTITION_NAME
                ),
                new Transform\SVM(
                    $svm,
                    Transform\DL::PARTITION_NAME
                )
            )
        );
    }
}