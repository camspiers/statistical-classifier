<?php

namespace Camspiers\StatisticalClassifier\Tokenizer;

class Word implements TokenizerInterface
{
    public function tokenize($document)
    {
        $matches = array();
        preg_match_all("/[\w]+/", $document, $matches, PREG_PATTERN_ORDER);

        return isset($matches[0]) ? $matches[0] : array();
    }
}
