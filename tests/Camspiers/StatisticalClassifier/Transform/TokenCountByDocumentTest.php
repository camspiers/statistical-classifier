<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Normalizer\Lowercase;
use Camspiers\StatisticalClassifier\Tokenizer\Word;

class TokenCountByDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $trasform = new TokenCountByDocument();
        
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
                    'spam' => array(
                        array(
                            'some',
                            'spam',
                            'document'
                        ),
                        array(
                            'another',
                            'spam',
                            'document'
                        )
                    ),
                    'ham'  => array(
                        array(
                            'some',
                            'ham',
                            'document'
                        ),
                        array(
                            'another',
                            'ham',
                            'document'
                        )
                    )
                )
            )
        );
    }
}
