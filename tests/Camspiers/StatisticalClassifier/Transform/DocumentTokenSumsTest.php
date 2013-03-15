<?php

namespace Camspiers\StatisticalClassifier\Transform;

class DocumentTokenSumsTest extends TransformTest
{
    public function testTransform()
    {
        $transform = new DocumentTokenSums(self::PARTITION_NAME);
        $transform->apply($this->index);
        $this->assertEquals(
            array(
                'spam' => array(
                    3,
                    3
                 ),
                'ham' => array(
                    3,
                    3
                 )
            ),
            $this->index->getPartition(DocumentTokenSums::PARTITION_NAME)
        );
    }
}
