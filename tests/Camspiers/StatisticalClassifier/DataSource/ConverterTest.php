<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $data = array(
            array(
                'document' => 'Test',
                'category' => 'Test'
            )
        );

        // Configure data source with a getData method that returns $data
        $convertFrom = $this->getMock(
            __NAMESPACE__ . '\\DataSourceInterface'
        );

        $convertFrom->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($data));

        // Configure data source that expects the setData method to be called once with $data as argument
        // and the write method to be called once
        $convertTo = $this->getMock(
            __NAMESPACE__ . '\\DataSourceInterface'
        );

        $convertTo->expects($this->once())
            ->method('setData')
            ->with($this->equalTo($data));

        $convertTo->expects($this->once())
            ->method('write');

        $converter = new Converter(
            $convertFrom,
            $convertTo
        );

        $converter->run();
    }
}
