<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

class Stopword implements NormalizerInterface
{
    protected $stopwords;

    public function __construct(array $stopwords)
    {
        $this->stopwords = $stopwords;
    }

    public function normalize(array $tokens)
    {
        return array_diff($tokens, $this->stopwords);
    }
}
