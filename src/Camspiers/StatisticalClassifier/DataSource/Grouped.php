<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class Grouped extends DataArray
{
    protected $dataSources = array();

    public function __construct($dataSources = null)
    {
        if (is_array($dataSources)) {
            $this->dataSources = $dataSources;
        }
        parent::__construct($this->read());
    }

    public function read()
    {
        $groupedData = array();
        foreach ($this->dataSources as $dataSource) {
            $groupedData = array_merge($groupedData, $dataSource->getData());
        }

        return $groupedData;
    }

    public function write()
    {
    }
}
