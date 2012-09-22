<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class DataArray implements DataSourceInterface, \Serializable
{

    protected $data = array();

    public function __construct($data)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    public function getCategories()
    {
        return array_keys($this->data);
    }

    public function hasCategory($category)
    {
        return array_key_exists($category, $this->data);
    }

    public function addCategory($category)
    {
        $this->data[$category] = array();
    }

    public function addDocument($category, $document)
    {
        if (!$this->hasCategory($category)) {
            $this->addCategory($category);
        }
        if (!in_array($document, $this->data[$category])) {
            $this->data[$category][] = $document;

            return true;
        } else {
            return false;
        }
    }

    public function categoryCount($category)
    {
        return isset($this->data[$category]) ? count($this->data[$category]) : 0;
    }

    public function documentCount()
    {
        $count = 0;
        foreach ($this->data as $documents) {
            $count += count($documents);
        }

        return $count;
    }

    public function read()
    {
        return $this->data;
    }

    public function getData($normalize = false)
    {
        if ($normalize) {
            $counts[] = array();
            $data = $this->data;
            foreach ($data as $category => $documents) {
                $counts[] = count($documents);
            }
            $min = min($counts);
            foreach ($data as $category => $documents) {
                $data[$category] = array_slice($documents, 0, $min, true);
            }
            return $data;
        }
        return $this->data;
    }

    public function write()
    {}

    public function serialize() {
        return serialize($this->data);
    }
    
    public function unserialize($data) {
        $this->data = unserialize($data);
    }

}
