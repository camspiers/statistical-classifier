<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

class Lowercase implements NormalizerInterface
{
    public function normalize(array $tokens)
    {
        return array_map(
            'strtolower',
            $tokens
        );
    }
}
