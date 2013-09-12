<?php

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\DataSource\DataArray;

class ComplementNaiveBayesTest extends \PHPUnit_Framework_TestCase
{
    public function testClassify()
    {
        $dataSource = new DataArray();
        $dataSource->addDocument('spam', 'Some spam');
        $dataSource->addDocument('ham', 'Some ham');
        
        $classifier = new ComplementNaiveBayes($dataSource);
        
        $this->assertEquals('spam', $classifier->classify('Some spam'));
        $this->assertEquals('ham', $classifier->classify('Some ham'));
    }
}
