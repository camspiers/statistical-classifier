<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Index\Index;
use PHPUnit_Framework_TestCase;

abstract class TransformTest extends PHPUnit_Framework_TestCase
{
    const PARTITION_NAME = 'test';

    protected function setUp()
    {
        $this->index = new Index(
            new DataArray(
                array(
                    'spam' => array(
                        'Some spam document',
                        'Another spam document'
                    ),
                    'ham' => array(
                        'Some ham document',
                        'Another ham document'
                    )
                )
            )
        );
        $this->index->setPartition(
            self::PARTITION_NAME,
            array(
                'spam' => array(
                    array(
                        'some' => 1,
                        'spam' => 1,
                        'document' => 1
                    ),
                    array(
                        'another' => 1,
                        'spam' => 1,
                        'document' => 1
                    )
                ),
                'ham' => array(
                    array(
                        'some' => 1,
                        'ham' => 1,
                        'document' => 1
                    ),
                    array(
                        'another' => 1,
                        'ham' => 1,
                        'document' => 1
                    )
                )
            )
        );
    }
}
