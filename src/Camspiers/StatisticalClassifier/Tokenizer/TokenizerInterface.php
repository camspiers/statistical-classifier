<?php

namespace Camspiers\StatisticalClassifier\Tokenizer;

interface TokenizerInterface
{
    public function tokenize($document);
}
