<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Tokenizer;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Word implements TokenizerInterface
{
    /**
     * @{inheritdoc}
     */
    public function tokenize($document)
    {
        $matches = array();
        preg_match_all("/[\w]+/", $document, $matches, PREG_PATTERN_ORDER);

        return isset($matches[0]) ? $matches[0] : array();
    }
}
