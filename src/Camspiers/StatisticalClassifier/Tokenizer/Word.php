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
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Word implements TokenizerInterface
{
    /**
     * @{inheritdoc}
     */
    public function tokenize($document)
    {
        return preg_split('/\PL+/u', $document, null, PREG_SPLIT_NO_EMPTY);
    }
}
