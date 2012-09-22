<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class Json extends DataArray
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        parent::__construct($this->read());
    }

    public function read()
    {
        if (file_exists($this->filename)) {
            $data = json_decode(file_get_contents($this->filename), true);
            if (is_array($data)) {
                return $data;
            }
        }

        return array();
    }

    public function write()
    {
        file_put_contents($this->filename, json_encode($this->data));
    }

}
