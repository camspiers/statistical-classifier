<?php
namespace Camspiers\StatisticalClassifier\Normalizer\Token;

class PhpStemmerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpStemmer
     */
    protected $phpStemmer;

    public function normalizeDataProvider()
    {
        return array(
            array(array('optimization'), array('optim'), 'english', 'utf-8'),
            array(array('optimisation'), array('optimis'), 'french', 'utf-8'),
            array(array('wzhgqkx'), array('wzhgq'), 'alien', 'utf-8', 'InvalidArgumentException'),
        );
    }

    /**
     * @covers \Camspiers\StatisticalClassifier\Normalizer\Token\PhpStemmer::normalize
     * @dataProvider normalizeDataProvider
     *
     * @param array   $words
     * @param array   $expected
     * @param string  $lang
     * @param string  $charset
     * @param boolean $expectedException
     */
    public function testNormalize(array $words, array $expected, $lang, $charset, $expectedException = false)
    {
        if (! extension_loaded('stemmer')) {
            $this->markTestSkipped('stemmer PHP extension not available');
        }

        if ($expectedException) {
            $this->setExpectedException($expectedException);
        }

        $stemmer = new PhpStemmer($lang, $charset);
        $this->assertEquals($expected, $stemmer->normalize($words));
    }
}
