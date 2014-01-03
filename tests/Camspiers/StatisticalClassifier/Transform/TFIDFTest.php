<?php

namespace Camspiers\StatisticalClassifier\Transform;

class TFIDFTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $tfidf = new TFIDF();

        $this->assertEquals(
            array(
                'spam' => array(
                    array(
                        'some'     => 0.090619058289457,
                        'spam'     => 0.090619058289457,
                        'document' => 0
                    ),
                    array(
                        'another'  => 0.090619058289457,
                        'spam'     => 0.090619058289457,
                        'document' => 0
                    )
                ),
                'ham'  => array(
                    array(
                        'some'     => 0.090619058289457,
                        'ham'      => 0.090619058289457,
                        'document' => 0
                    ),
                    array(
                        'another'  => 0.090619058289457,
                        'ham'      => 0.090619058289457,
                        'document' => 0
                    )
                )
            ),
            $tfidf(
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
                ),
                4,
                array(
                    'some'     => 2,
                    'spam'     => 2,
                    'document' => 4,
                    'another'  => 2,
                    'ham'      => 2
                )
            )
        );
    }
}
