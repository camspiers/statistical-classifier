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
class TFIDF
{
    public function __invoke(
        $tokenCountByDocument,
        $documentCount,
        $tokenAppreanceCount
    ) {
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $documentModel => $document) {
                foreach ($document as $token => $count) {
                    $tokenCountByDocument
                        [$category]
                        [$documentModel]
                        [$token] = log($count + 1, 10) * log(
                            $documentCount / $tokenAppreanceCount[$token],
                            10
                        );
                }
            }
        }

        return $tokenCountByDocument;
    }
}
