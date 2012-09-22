<?php

namespace Camspiers\StatisticalClassifier\Classifiers;

interface ClassifierInterface
{
    public function classify($document);
    public function update($category, $document);
}
