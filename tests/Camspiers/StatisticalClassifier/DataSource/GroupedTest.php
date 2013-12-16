<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class GroupedTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectCreateViaArray()
    {
        $sources = array(
            $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface'),
            $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface')
        );
        
        $object = new Grouped($sources);
        
        $this->assertInstanceOf(__NAMESPACE__ . '\\' . 'Grouped', $object);
        
        $this->assertEquals(2, count($object->getDataSources()));
    }
    
    public function testObjectCreateViaDynamicArgs()
    {
        $object = new Grouped(
            $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface'),
            $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface')
        );

        $this->assertInstanceOf(__NAMESPACE__ . '\\' . 'Grouped', $object);

        $this->assertEquals(2, count($object->getDataSources()));
    }
    
    public function testRead()
    {
        $source1 = $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface');
        $source1->expects($this->once())->method('getData')->will($this->returnValue(array(
            array(
                'document' => 'Hello',
                'category' => 'super'
            )
        )));
        $source2 = $this->getMock(__NAMESPACE__ . '\\' . 'DataSourceInterface');
        $source2->expects($this->once())->method('getData')->will($this->returnValue(array(
            array(
                'document' => 'Hello',
                'category' => 'super'
            )
        )));
        
        $object = new Grouped($source1, $source2);
        
        $this->assertEquals(
            array(
                array(
                    'document' => 'Hello',
                    'category' => 'super'
                ),
                array(
                    'document' => 'Hello',
                    'category' => 'super'
                )
            ),
            $object->read()
        );
    }
}
