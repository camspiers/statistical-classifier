<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

class Stopword implements NormalizerInterface
{
    protected $stopwords;
    protected $normalizer;

    public function __construct(array $stopwords, NormalizerInterface $normalizer = null)
    {
        $this->stopwords = $stopwords;
        $this->normalizer = $normalizer;
    }

    public function normalize(array $tokens)
    {
        $tokens = array_diff($tokens, $this->stopwords);
        return $this->normalizer instanceof NormalizerInterface ? $this->normalizer->normalize($tokens) : $tokens;
    }
}
