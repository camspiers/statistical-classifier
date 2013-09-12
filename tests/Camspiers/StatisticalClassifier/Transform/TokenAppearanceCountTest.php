<?php

namespace Camspiers\StatisticalClassifier\Transform;

class TokenAppearanceCountTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transform = new TokenAppearanceCount();

        $this->assertEquals(
            array(
                'some'     => 2,
                'spam'     => 2,
                'document' => 4,
                'another'  => 2,
                'ham'      => 2
            ),
            $transform(
                array(
                    'spam' => array(
                        array(
                            'some'     => 1,
                            'spam'     => 1,
                            'document' => 1
                        ),
                        array(
                            'another'  => 1,
                            'spam'     => 1,
                            'document' => 1
                        )
                    ),
                    'ham'  => array(
                        array(
                            'some'     => 1,
                            'ham'      => 1,
                            'document' => 1
                        ),
                        array(
                            'another'  => 1,
                            'ham'      => 1,
                            'document' => 1
                        )
                    )
                )
            )
        );
    }
}
