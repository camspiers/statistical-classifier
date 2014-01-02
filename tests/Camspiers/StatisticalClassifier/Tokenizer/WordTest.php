<?php

namespace Camspiers\StatisticalClassifier\Tokenizer;

class WordTest extends \PHPUnit_Framework_TestCase
{
    protected $word;

    protected function setUp()
    {
        $this->word = new Word;
    }

    protected function tearDown()
    {
        $this->word = null;
    }

    /**
     * @covers Camspiers\StatisticalClassifier\Tokenizer\Word::tokenize
     */
    public function testTokenize()
    {
        $this->assertEquals(
            array(
                'this',
                'is',
                'a',
                'français',
            ),
            $this->word->tokenize(
                'this is a "français"'
            )
        );
    }
}
