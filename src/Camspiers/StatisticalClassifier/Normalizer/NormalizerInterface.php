<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

interface NormalizerInterface
{
    public function normalize(array $tokens);
}
