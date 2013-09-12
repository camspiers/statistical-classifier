<?php

namespace Camspiers\StatisticalClassifier\Transform;

class DocumentCountTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transform = new DocumentCount();
        $this->assertEquals(
            2,
            $transform(array(1, 2))
        );
        $this->assertEquals(
            3,
            $transform(array(1, 2, 3))
        );
    }
}
