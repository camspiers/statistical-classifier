<?php

namespace Camspiers\StatisticalClassifier\Normalizers;

interface NormalizerInterface
{
    public function normalize(array $tokens);
}
