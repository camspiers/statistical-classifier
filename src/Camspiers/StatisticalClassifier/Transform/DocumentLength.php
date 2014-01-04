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
class DocumentLength
{
    public function __invoke($tfidf)
    {
        $transform = $tfidf;

        foreach ($tfidf as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                $denominator = 0;
                foreach ($document as $count) {
                    $denominator += $count * $count;
                }
                $denominator = sqrt($denominator);
                if ($denominator != 0) {
                    foreach ($document as $token => $count) {
                        $transform
                            [$category]
                            [$documentIndex]
                            [$token] = $count / $denominator;
                    }
                } else {
                    throw new \RuntimeException("Cannot divide by 0 in DocumentLength transform");
                }
            }
        }

        return $transform;
    }
}
