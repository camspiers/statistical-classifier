<?php
namespace Camspiers\StatisticalClassifier\Document;

/**
 * @backupStaticAttributes disabled
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function constructDataProvider()
    {
        return [
            [
                'Some document data in a document',
                ['document' => 2, 'data' => 1, 'in' => 1, 'a' => 1, 'Some' => 1],
            ],
            [
                ['Some', 'document', 'data', 'in', 'a', 'document'],
                ['document' => 2, 'data' => 1, 'in' => 1, 'a' => 1, 'Some' => 1],
            ],
            [
                new \ArrayObject(['Some', 'document', 'data', 'in', 'a', 'document']),
                ['document' => 2, 'data' => 1, 'in' => 1, 'a' => 1, 'Some' => 1],
            ],
            [
                new \stdClass(),
                'InvalidArgumentException',
            ],
        ];
    }

    /**
     * @covers \Camspiers\StatisticalClassifier\Document\Document::__construct
     * @dataProvider constructDataProvider
     * @param mixed        $data
     * @param string|array $expected
     */
    public function testConstruct($data, $expected)
    {
        if (is_string($expected)) {
            $this->setExpectedException($expected);
        }

        $document = new Document($data);

        $this->assertEquals($document->getArrayCopy(), (array) $document);
        $this->assertEquals($expected, $document->getArrayCopy());
    }

    public function testExchangeArrayUsesOffsetSet()
    {
        Document::setTokenNormalizer('strtolower');

        $document           = new Document();
        $document->Some     = 1;
        $document->document = 2;
        $document->data     = 1;
        $document->in       = 1;
        $document->a        = 1;

        $this->assertSame(
            ['some' => 1, 'document' => 2, 'data' => 1, 'in' => 1, 'a' => 1],
            (array) $document
        );
        $this->assertCount(5, $document); // Token frequency, thus, document is counted only once
    }

    public function testIsCountable()
    {
        $document = new Document(['Some', 'document', 'data', 'in', 'a', 'document']);

        $this->assertCount(5, $document); // Token frequency, thus, document is counted only once
    }
}
