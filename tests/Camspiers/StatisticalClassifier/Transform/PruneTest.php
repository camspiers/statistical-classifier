<?php

namespace Camspiers\StatisticalClassifier\Transform;

class PruneTest extends TransformTest
{
    public function testRemove()
    {
        $transform = new Prune();
        $transform->apply($this->index);
        $this->assertEquals(
            array(),
            $this->index->getPartitions()
        );
    }

    public function testExclude()
    {
        $transform = new Prune(array(self::PARTITION_NAME));
        $transform->apply($this->index);
        $this->assertEquals(
            array(
                self::PARTITION_NAME
            ),
            $this->index->getPartitions()
        );
    }
}
