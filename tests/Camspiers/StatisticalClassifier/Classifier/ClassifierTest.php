<?php

namespace Camspiers\StatisticalClassifier\Classifier;

class ClassifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Classifier
     */
    protected $classifier;

    protected $dataSource;

    protected $model;

    protected function setUp()
    {
        $this->classifier = $this->getMockForAbstractClass(
            __NAMESPACE__.'\Classifier'
        );

        $this->classifier->setDataSource(
            $this->dataSource = $this->getMock(
                'Camspiers\StatisticalClassifier\DataSource\DataSourceInterface'
            )
        );

        $this->classifier->setModel(
            $this->model = $this->getMock(
                'Camspiers\StatisticalClassifier\Model\ModelInterface'
            )
        );
    }

    public function testIs()
    {
        $this->dataSource->expects($this->once())
            ->method('hasCategory')
            ->with('test')
            ->will($this->returnValue(true));

        $this->classifier->expects($this->once())
            ->method('classify')
            ->with('document')
            ->will($this->returnValue('test'));

        $this->assertTrue(
            $this->classifier->is('test', 'document')
        );
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The category 'test' doesn't exist
     */
    public function testIsException()
    {
        $this->dataSource->expects($this->once())
            ->method('hasCategory')
            ->with('test')
            ->will($this->returnValue(false));

        $this->assertTrue(
            $this->classifier->is('test', 'document')
        );
    }
}
