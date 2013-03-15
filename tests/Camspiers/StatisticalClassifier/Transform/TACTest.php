<?php

namespace Camspiers\StatisticalClassifier\Transform;

class TACTest extends TransformTest
{
    public function testTransform()
    {
        $transform = new TAC(self::PARTITION_NAME);
        $transform->apply($this->index);
        $data = $this->index->getPartition(TAC::PARTITION_NAME);
        $this->assertEquals(
            array(
                'some' => 2,
                'spam' => 2,
                'document' => 4,
                'another' => 2,
                'ham' => 2
            ),
            $data
        );
    }
}
