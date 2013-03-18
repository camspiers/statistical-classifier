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

class Lowercase implements NormalizerInterface
{
    public function normalize(array $tokens)
    {
        return array_map(
            'strtolower',
            $tokens
        );
    }
}
