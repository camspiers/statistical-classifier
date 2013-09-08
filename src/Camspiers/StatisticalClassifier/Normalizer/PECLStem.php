<?php

/**
 * This file is part of the Statistical Classifier package.
 * It provides support for stemming via the PECL stem package 
 * in a variety of languages.
 * 
 * http://pecl.php.net/package/stem
 *
 * (c) Stanislav Khromov <stanislav.khromov@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer;

/**
 * @author  Stanislav Khromov <stanislav.khromov@gmail.com>
 * @package Statistical Classifier
 */
class PECLStem implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(array $tokens)
    {
        return array_map(
            function ($token) {
                return stem_english(strtolower($token));
            },
            $tokens
        );
    }
}