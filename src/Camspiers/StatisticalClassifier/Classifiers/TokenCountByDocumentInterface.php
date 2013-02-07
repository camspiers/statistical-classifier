<?php

namespace Camspiers\StatisticalClassifier\Classifiers;

interface TokenCountByDocumentInterface extends ClassifierInterface
{
    public function getTokenCountByDocument();
    public function setTokenCountByDocument($tokenCountByDocument);
}
