<?php

namespace Camspiers\StatisticalClassifier\Heuristics;

use Camspiers\StatisticalClassifier\Classifiers\ClassifierInterface;
use Camspiers\StatisticalClassifier\Classifiers\TokenCountByDocumentInterface;
use RuntimeException;

class IDF implements HeuristicInterface
{
    private $tokenSums = array();

    public function apply(ClassifierInterface $classifier)
    {
        if (!$classifier instanceof TokenCountByDocumentInterface) {
            throw new RuntimeException('Classifier must implement TokenCountByDocumentInterface');
        }
        $documentCount = $classifier->getSource()->getDocumentCount();
        $transform = $tokenCountByDocument = $classifier->getTokenCountByDocument();
        foreach ($tokenCountByDocument as $category => $documents) {
            foreach ($documents as $index => $document) {
                foreach ($document as $token => $count) {
                    $transform
                        [$category]
                        [$index]
                        [$token] = $count * log(
                            $documentCount / $this->getTokenSum($tokenCountByDocument, $token),
                            10
                        );
                }
            }
        }
        $classifier->setTokenCountByDocument($transform);
    }

    protected function getTokenSum($tokenCountByDocument, $token)
    {
        if (!array_key_exists($token, $this->tokenSums)) {
            $sum = 0;
            foreach ($tokenCountByDocument as $category => $documents) {
                foreach ($documents as $documentIndex => $document) {
                    if (array_key_exists($token, $document) && $document[$token] > 0) {
                        $sum++;
                    }
                }
            }
            $this->tokenSums[$token] = $sum;
        }

        return $this->tokenSums[$token];
    }
}
