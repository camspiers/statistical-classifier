<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class PDO extends DataArray
{
    private $queries;

    public function __construct($queries = array()) {
        $this->queries = $queries;
        parent::__construct($this->read());
    }

    public function read()
    {
        $data = array();

        if (is_array($this->queries)) {
            foreach ($this->queries as $query) {
                $category = $query->getCategory();
                if (isset($data[$category]) && is_array($data[$category])) {
                    $data[$category] = array_merge($data[$category], $query->read());
                } else {
                    $data[$category] = $query->read();
                }
            }
        }

        return $data;
    }

}
