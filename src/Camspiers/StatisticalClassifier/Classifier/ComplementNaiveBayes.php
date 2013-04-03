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
 * An implementation of a Naive Bayes classifier.
 *
 * This classifier is based off *Tackling the Poor Assumptions of Naive Bayes Text Classifiers* by Jason Rennie
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class ComplementNaiveBayes extends GenericClassifier
{
    /**
     * Create the Naive Bayes Classifier
     * @param IndexInterface      $index      An index to modify with transforms
     * @param TokenizerInterface  $tokenizer  The tokenizer to break up the documents
     * @param NormalizerInterface $normalizer The normaizer to make tokens consistent
     */
    public function __construct(
        IndexInterface $index,
        TokenizerInterface $tokenizer = null,
        NormalizerInterface $normalizer = null
    ) {
        $tokenizer = $tokenizer ?: new Word();
        $normalizer = $normalizer ?: new Lowercase();
        parent::__construct(
            $index,
            new ClassificationRule\ComplementNaiveBayes(
                Transform\Weight::PARTITION_NAME
            ),
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
                new Transform\TBC(
                    Transform\TCBD::PARTITION_NAME
                ),
                new Transform\DocumentTokenSums(
                    Transform\DL::PARTITION_NAME
                ),
                new Transform\DocumentTokenCounts(
                    Transform\DL::PARTITION_NAME
                ),
                new Transform\Complement(
                    Transform\DL::PARTITION_NAME
                ),
                new Transform\Weight(
                    Transform\Complement::PARTITION_NAME
                ),
                new Transform\Prune(
                    array(
                        Transform\Weight::PARTITION_NAME
                    )
                )
            )
        );
    }
}
