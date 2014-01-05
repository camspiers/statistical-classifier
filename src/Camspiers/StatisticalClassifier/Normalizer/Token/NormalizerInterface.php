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
interface NormalizerInterface
{
    /**
     * Makes tokens more consistent by a particular method.
     *
     * This is to increase matches across what tokens are deemed equivalent but differnt
     * @param  array $tokens The tokens to normalizer
     * @return array The array of normalized tokens
     */
    public function normalize(array $tokens);
}
