<?php

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\Transform\TransformInterface;

interface ClassifierInterface
{
    public function classify($document);
    public function prepareIndex();
    public function addTransform(TransformInterface $transform);
    public function setTransforms(array $transforms);
}
