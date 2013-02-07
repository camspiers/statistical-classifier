<?php

namespace Camspiers\StatisticalClassifier\Heuristics;

use Camspiers\StatisticalClassifier\Classifiers\ClassifierInterface;
use Camspiers\StatisticalClassifier\Classifiers\TokenCountByDocumentInterface;
use RuntimeException;

class TF implements HeuristicInterface
{
    public function apply(ClassifierInterface $classifier)
    {
        if (!$classifier instanceof TokenCountByDocumentInterface) {
            throw new RuntimeException('Classifier must implement TokenCountByDocumentInterface');
        }
        $transform = $tokenCountByDocument = $classifier->getTokenCountByDocument();
        $bench = new \Ubench;
        $bench->start();
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $index => $document) {
                foreach ($document as $token => $count) {
                    $transform
                        [$category]
                        [$index]
                        [$token] = log($count + 1, 10);
                }
            }
        }
        $bench->end();
        echo $bench->getTime();
        $classifier->setTokenCountByDocument($transform);
    }
}
