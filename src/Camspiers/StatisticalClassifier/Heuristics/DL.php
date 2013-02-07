<?php

namespace Camspiers\StatisticalClassifier\Heuristics;

use Camspiers\StatisticalClassifier\Classifiers\ClassifierInterface;
use Camspiers\StatisticalClassifier\Classifiers\TokenCountByDocumentInterface;
use RuntimeException;

class DL implements HeuristicInterface
{
    public function apply(ClassifierInterface $classifier)
    {
        if (!$classifier instanceof TokenCountByDocumentInterface) {
            throw new RuntimeException('Classifier must implement TokenCountByDocumentInterface');
        }
        $transform = $tokenCountByDocument = $classifier->getTokenCountByDocument();
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $index => $document) {
                $denominator = 0;
                foreach ($document as $count) {
                    $denominator += $count * $count;
                }
                $denominator = sqrt($denominator);
                if ($denominator == 0) {
                    continue;
                }
                foreach ($document as $token => $count) {
                    $transform
                        [$category]
                        [$index]
                        [$token] = $count / $denominator;
                }
            }
        }

        $classifier->setTokenCountByDocument($transform);
    }
}
