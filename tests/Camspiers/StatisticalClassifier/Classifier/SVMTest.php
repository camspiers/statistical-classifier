<?php

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\DataSource\DataArray;

/**
 * Class SVMTest
 * @package Camspiers\StatisticalClassifier\Classifier
 * @group svm
 */
class SVMTest extends \PHPUnit_Framework_TestCase
{
    public function testClassify()
    {
        $dataSource = new DataArray();
        $dataSource->addDocument('spam', 'Some spam');
        $dataSource->addDocument('ham', 'Some ham');

        $classifier = new SVM($dataSource);

        $this->assertEquals('spam', $classifier->classify('Some spam'));
        $this->assertEquals('ham', $classifier->classify('Some ham'));
    }
}
