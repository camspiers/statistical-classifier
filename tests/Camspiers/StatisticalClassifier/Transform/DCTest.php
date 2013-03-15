<?php

namespace Camspiers\StatisticalClassifier\Transform;

class DCTest extends TransformTest
{
    public function testTransform()
    {
        $transform = new DC(self::PARTITION_NAME);
        $transform->apply($this->index);
        $data = $this->index->getPartition(DC::PARTITION_NAME);
        $this->assertEquals(
            4,
            $data
        );
    }
}
