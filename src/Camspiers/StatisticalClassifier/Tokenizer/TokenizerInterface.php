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
interface TokenizerInterface
{
    /**
     * Split document into tokens
     * @param  string $document The document to split
     * @return array  An array of tokens
     */
    public function tokenize($document);
}
