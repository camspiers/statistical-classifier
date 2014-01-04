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
class Weight
{
    public function __invoke($data)
    {
        foreach ($data as $category => $tokens) {
            foreach ($tokens as $token => $value) {
                $data[$category][$token] = log($value, 10);
            }
        }

        return $data;
    }
}
