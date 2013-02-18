<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

interface TransformInterface
{
    public function apply(IndexInterface $index);
}
