<?php

namespace Camspiers\StatisticalClassifier\Tokenizers;

interface TokenizerInterface
{
    public function tokenize($document);
}
