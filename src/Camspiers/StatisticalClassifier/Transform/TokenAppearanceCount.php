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
class TokenAppearanceCount
{
    public function __invoke($tokenCountByDocument)
    {
        $transform = array();
        foreach ($tokenCountByDocument as $documents) {
            foreach ($documents as $document) {
                foreach ($document as $token => $count) {
                    if ($count > 0) {
                        if (!isset($transform[$token])) {
                            $transform[$token] = 0;
                        }
                        $transform[$token]++;
                    }
                }
            }
        }

        return $transform;
    }
}
