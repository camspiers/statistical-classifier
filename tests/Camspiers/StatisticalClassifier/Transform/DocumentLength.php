<?php

namespace Camspiers\StatisticalClassifier\Transform;

class DocumentLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $transform = new DocumentLength();
        
        $this->assertEquals(
            array(
                'cat' => array(
                    array(
                        0.44721359549996,
                        0.89442719099992
                    ),
                    array(
                        0.6,
                        0.8
                    ),
                    array(
                        0.44721359549996,
                        0.89442719099992
                    )
                )
            ),
            $transform(
                array(
                    'cat' => array(
                        array(
                            1,
                            2
                        ),
                        array(
                            3,
                            4
                        ),
                        array(
                            1,
                            2
                        )
                    )
                )
            )
        );
    }
}
