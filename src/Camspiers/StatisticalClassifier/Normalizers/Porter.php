<?php

namespace Camspiers\StatisticalClassifier\Normalizers;

class Porter implements NormalizerInterface
{
    public function normalize(array $tokens)
    {
        return array_map(
            function ($token) {
                return \Porter::Stem(strtolower($token));
            },
            $tokens
        );
    }
}
