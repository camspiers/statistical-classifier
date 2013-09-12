<?php

namespace Camspiers\StatisticalClassifier\Transform;

class DocumentTokenCountsTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $transform = new DocumentTokenCounts();
        
        $this->assertEquals(
            array(
                'cat' => 110,
                'cat2' => 200
            ),
            $transform(
                array(
                    'cat' => array(
                        range(1, 10),
                        range(1, 100)
                    ),
                    'cat2' => array(
                        range(1, 100),
                        range(1, 100)
                    )
                )
            )
        );
    }
}
