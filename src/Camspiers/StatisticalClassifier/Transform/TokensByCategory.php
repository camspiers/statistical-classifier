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
class TokensByCategory
{
    public function __invoke($tokenCountbyDocument)
    {
        $transform = array();

        foreach ($tokenCountbyDocument as $category => $documents) {
            $transform[$category] = array();
            foreach ($documents as $document) {
                foreach (array_keys($document) as $token) {
                    if (array_key_exists($token, $transform[$category])) {
                        $transform[$category][$token] += $document[$token];
                    } else {
                        $transform[$category][$token] = $document[$token];
                    }
                }
            }
        }

        return $transform;
    }
}
