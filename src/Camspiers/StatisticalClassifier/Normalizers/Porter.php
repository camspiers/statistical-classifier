<?php

namespace Camspiers\StatisticalClassifier\Normalizers;

use Camspiers\StatisticalClassifier\Stemmers\Porter;

class Porter implements NormalizerInterface
{
    public function normalize(array $tokens)
    {
        return array_map(
            array($this, 'normalizeToken'),
            $tokens
        );
    }

    protected function normalizeToken($token)
    {
        return Porter::Stem(strtolower($token));
    }
}
