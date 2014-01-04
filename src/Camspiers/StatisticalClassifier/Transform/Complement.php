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
class Complement
{
    public function __invoke(
        $documentLength,
        $tokensByCategory,
        $documentCount,
        $documentTokenCounts
    ) {
        $cats = array_keys($tokensByCategory);
        $trans = array();

        $tokByCatSums = array();

        foreach ($tokensByCategory as $cat => $tokens) {
            $tokByCatSums[$cat] = array_sum($tokens);
        }

        $documentCounts = array();

        foreach ($documentLength as $cat => $documents) {
            $documentCounts[$cat] = count($documents);
        }

        foreach ($tokensByCategory as $cat => $tokens) {

            $trans[$cat] = array();
            $categoriesSelection = array_diff($cats, array($cat));

            $docsInOtherCats = $documentCount - $documentCounts[$cat];

            foreach (array_keys($tokens) as $token) {
                $trans[$cat][$token] = $docsInOtherCats;
                foreach ($categoriesSelection as $currCat) {
                    if (array_key_exists($token, $tokensByCategory[$currCat])) {
                        $trans[$cat][$token] += $tokensByCategory[$currCat][$token];
                    }
                }
                foreach ($categoriesSelection as $currCat) {
                    $trans[$cat][$token] =
                        $trans[$cat][$token]
                        /
                        ($tokByCatSums[$currCat] + $documentTokenCounts[$currCat]);
                }

            }

        }

        return $trans;
    }
}
