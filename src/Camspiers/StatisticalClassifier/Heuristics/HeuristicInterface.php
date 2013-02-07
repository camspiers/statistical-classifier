<?php

namespace Camspiers\StatisticalClassifier\Heuristics;

use Camspiers\StatisticalClassifier\Classifiers\ClassifierInterface;

interface HeuristicInterface
{
    public function apply(ClassifierInterface $classifier);
}
