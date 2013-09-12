<?php

namespace Camspiers\StatisticalClassifier\Transform;

class TokensByCategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transform = new TokensByCategory();
        
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
