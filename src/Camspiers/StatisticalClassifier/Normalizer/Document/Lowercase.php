<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer\Document;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Lowercase implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($document)
    {
        return mb_strtolower($document, 'utf-8');
    }
}
