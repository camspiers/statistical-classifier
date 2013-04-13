<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

class StopwordTest extends \PHPUnit_Framework_TestCase
{
    protected $stopword;

    protected function setUp()
    {
        $this->stopword = new Stopword(
            array(
                'stopword',
                'anotherstopword'
            )
        );
    }

    protected function tearDown()
    {
        $this->stopword = null;
    }
    
    public function testNormalize()
    {
        $this->assertEquals(
            array(
                'this',
                'is',
                'a',
                'and'
            ),
            array_values(
                $this->stopword->normalize(
                    array(
                        'this',
                        'is',
                        'a',
                        'stopword',
                        'and',
                        'anotherstopword'
                    )
                )
            )
        );
    }
}
