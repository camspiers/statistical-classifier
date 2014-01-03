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

use Camspiers\StatisticalClassifier\Normalizer\Document\NormalizerInterface as DocumentNormalizerInterface;
use Camspiers\StatisticalClassifier\Normalizer\Token\NormalizerInterface as TokenNormalizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class TokenCountByDocument
{
    protected $documentNormalizer;
    protected $tokenizer;
    protected $tokenNormalizer;

    /**
     * @param TokenizerInterface          $tokenizer
     * @param DocumentNormalizerInterface $documentNormalizer
     * @param TokenNormalizerInterface    $tokenNormalizer
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        DocumentNormalizerInterface $documentNormalizer = null,
        TokenNormalizerInterface $tokenNormalizer = null
    ) {
        $this->documentNormalizer = $documentNormalizer;
        $this->tokenizer          = $tokenizer;
        $this->tokenNormalizer    = $tokenNormalizer;
    }

    public function __invoke($data)
    {
        $transform = array();

        foreach ($data as $category => $documents) {
            $transform[$category]  = array();
            foreach ($documents as $document) {
                if ($this->documentNormalizer) {
                    $document = $this->documentNormalizer->normalize($document);
                }

                $tokens = $this->tokenizer->tokenize($document);

                if ($this->tokenNormalizer) {
                    $tokens = $this->tokenNormalizer->normalize($tokens);
                }

                $transform[$category][] = array_count_values($tokens);
            }
        }

        return $transform;
    }
}
