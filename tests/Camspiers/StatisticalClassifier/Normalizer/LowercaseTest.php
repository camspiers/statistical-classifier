<?php

namespace Camspiers\StatisticalClassifier\Normalizer;

class LowercaseTest extends \PHPUnit_Framework_TestCase
{
    protected $lowercase;

    protected function setUp()
    {
        $this->lowercase = new Lowercase();
    }

    protected function tearDown()
    {
        $this->lowercase = null;
    }

    /**
     * @covers Camspiers\StatisticalClassifier\Normalizer\Lowercase::normalize
     */
    public function testNormalize()
    {
        $this->assertEquals(
            array(
                'this',
                'is',
                'some',
                'content'
            ),
            $this->lowercase->normalize(
                array(
                    'This',
                    'is',
                    'Some',
                    'Content'
                )
            )
        );
    }
}
