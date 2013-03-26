<?php

namespace Camspiers\StatisticalClassifier\ClassificationRule;

use Camspiers\StatisticalClassifier\Index\Index;

class NaiveBayesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Index
     */
    protected $index;
    /**
     * @var NaiveBayes
     */
    protected $rule;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->index = new Index;
        $this->rule = new NaiveBayes('test');
    }

    /**
     * @covers Camspiers\StatisticalClassifier\ClassificationRule\NaiveBayes::classify
     */
    public function testClassify()
    {
        $this->index->setPartition(
            'test',
            array(
                'spam' => array(
                    'spamtoken' => -1
                ),
                'ham' => array(
                    'hamtoken' => -1
                )
            )
        );
        $this->assertEquals(
            'ham',
            $this->rule->classify(
                $this->index,
                array(
                    'hamtoken',
                    'hamtoken',
                    'spamtoken'
                )
            )
        );
        $this->index->setPartition(
            'test',
            array(
                'spam' => array(
                    'spamtoken' => -2
                ),
                'ham' => array(
                    'hamtoken' => -0.75
                )
            )
        );
        $this->assertEquals(
            'spam',
            $this->rule->classify(
                $this->index,
                array(
                    'hamtoken',
                    'hamtoken',
                    'spamtoken'
                )
            )
        );
    }
}
