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

use Porter as PorterStemmer;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Porter implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(array $tokens)
    {
        return array_map(
            function ($token) {
                return PorterStemmer::Stem(strtolower($token));
            },
            $tokens
        );
    }
}
