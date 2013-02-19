<?php

namespace Camspiers\StatisticalClassifier\Classifier;

interface ClassifierInterface
{
    public function classify($document);
}
