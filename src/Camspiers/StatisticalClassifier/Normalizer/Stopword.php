<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer;

class Stopword implements NormalizerInterface
{
    protected $stopwords;

    public function __construct(array $stopwords)
    {
        $this->stopwords = $stopwords;
    }

    public function normalize(array $tokens)
    {
        return array_diff($tokens, $this->stopwords);
    }
}
