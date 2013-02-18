<?php

namespace Camspiers\StatisticalClassifier\ClassificationRule;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

interface ClassificationRuleInterface
{
    public function classify(IndexInterface $index, $document);
}
