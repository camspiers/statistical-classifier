<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class Serialized extends DataArray
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        parent::__construct();
    }

    public function getData()
    {
        if (!is_array($this->data) || array() === $this->data) {
            $this->data = $this->read();
        }

        return $this->data;
    }

    public function read()
    {
        if (file_exists($this->filename)) {
            $data = unserialize(file_get_contents($this->filename));
            if (is_array($data)) {
                return $data;
            }
        }

        return array();
    }

    public function write()
    {
        file_put_contents($this->filename, serialize($this->data));
    }

}
