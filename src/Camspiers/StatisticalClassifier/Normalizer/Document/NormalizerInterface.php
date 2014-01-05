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
interface NormalizerInterface
{
    /**
     * Makes document more consistent by a particular method.
     *
     * @param  string $document The document to normalize
     * @return string The normalized document
     */
    public function normalize($document);
}
