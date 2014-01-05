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

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class TokenCountByDocument
{
    public function __invoke($data)
    {
        $transform = array();

        foreach ($data as $category => $documents) {
            $transform[$category]  = array();
            foreach ($documents as $tokens) {
                $transform[$category][] = array_count_values($tokens);
            }
        }

        return $transform;
    }
}
