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

use Porter as PorterStemmer;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Porter implements NormalizerInterface
{
    public function normalize(array $tokens)
    {
        $new = PorterStemmer::Stem(array(
            'hello'
        ));
        return array_map(
            function ($token) {
                return PorterStemmer::Stem(strtolower($token));
            },
            $tokens
        );
    }
}
