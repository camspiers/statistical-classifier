<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Normalizer\Lowercase;
use Camspiers\StatisticalClassifier\Tokenizer\Word;

class TokenCountByDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $trasform = new TokenCountByDocument(
            new Word(),
            new Lowercase()
        );
        
        $this->assertEquals(

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
            $trasform(
                array(
                    array(
                        'category' => 'spam',
                        'document' => 'Some spam document'
                    ),
                    array(
                        'category' => 'spam',
                        'document' => 'Another spam document'
                    ),
                    array(
                        'category' => 'ham',
                        'document' => 'Some ham document'
                    ),
                    array(
                        'category' => 'ham',
                        'document' => 'Another ham document'
                    )
                )
            )
        );
    }
}
