<?php

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

use PHPUnit_Framework_TestCase;

class IndexTest extends PHPUnit_Framework_TestCase
{
    protected $index;

    protected function setUp()
    {
        $this->index = new Index();
    }

    public function testPrepared()
    {
        $this->assertFalse($this->index->isPrepared());
        $this->index->setPrepared(true);
        $this->assertTrue($this->index->isPrepared());
    }

    public function testData()
    {
        $this->assertTrue($this->index->getDataSource() instanceof DataSourceInterface);
        $this->index->setDataSource(
            new DataArray(
                $data = array(
                    array(
                        'category' => 'test',
                        'document' => 'test'
                    )
                )
            )
        );
        $this->assertEquals($data, $this->index->getDataSource()->getData());
    }

    public function testPartitions()
    {
        $this->assertEquals(array(), $this->index->getPartitions());
        $this->index->setPartition('test', true);
        $this->assertEquals(array('test'), $this->index->getPartitions());
        $this->assertTrue($this->index->getPartition('test'));
        $this->index->removePartition('test');
        $this->assertEquals(array(), $this->index->getPartitions());
    }
    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage The index partition 'test' does not exist
     */
    public function testGetPartition()
    {
        $this->index->getPartition('test');
    }
}
