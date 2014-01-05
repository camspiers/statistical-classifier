<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer\Token;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Stopword implements NormalizerInterface
{
    /**
     * An array of words to filter
     * @var array
     */
    protected $stopwords;
    /**
     * Create the normalizer from an array of stopwords
     * @param array $stopwords The array of stopwords
     */
    public function __construct(array $stopwords)
    {
        $this->stopwords = $stopwords;
    }
    /**
     * {@inheritdoc}
     */
    public function normalize(array $tokens)
    {
        return array_diff($tokens, $this->stopwords);
    }
}
