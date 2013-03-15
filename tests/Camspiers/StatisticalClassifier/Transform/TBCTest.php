<?php

namespace Camspiers\StatisticalClassifier\Transform;

class TBCTest extends TransformTest
{
    public function testTransform()
    {
        $transform = new TBC(self::PARTITION_NAME);
        $transform->apply($this->index);
        $data = $this->index->getPartition(TBC::PARTITION_NAME);
        $this->assertEquals(
            array(
                'spam' => array(
                    'some' => true,
                    'spam' => true,
                    'document' => true,
                    'another' => true
                ),
                'ham' => array(
                    'some' => true,
                    'ham' => true,
                    'document' => true,
                    'another' => true
                )
            ),
            $data
        );
    }
}
