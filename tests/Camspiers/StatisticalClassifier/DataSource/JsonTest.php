<?php

namespace Camspiers\StatisticalClassifier\DataSource;

use org\bovigo\vfs\vfsStream;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $object;
    /**
     * @var
     */
    protected $json;
    /**
     * @var
     */
    protected $data;
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        vfsStream::setup();
        $this->data = array(
            array(
                'category' => 'test',
                'document' => 'Some document'
            ),
            array(
                'category' => 'test2',
                'document' => 'Some document'
            )
        );
        file_put_contents($url = vfsStream::url('root/read.json'), $this->json = json_encode($this->data));
        $this->object = new Json($url);
    }
    public function testRead()
    {
        $this->assertEquals(
            array(
                'test' => array(
                    'Some document'
                ),
                'test2' => array(
                    'Some document'
                )
            ),
            $this->object->getData()
        );
    }

    public function testWrite()
    {
        $url = vfsStream::url('root/write.json');
        $test = new Json($url);
        $test->setData($this->data);
        $test->write();
        $this->assertTrue(
            file_exists($url)
        );
        $this->assertEquals(
            $this->json,
            file_get_contents($url)
        );
    }
}
