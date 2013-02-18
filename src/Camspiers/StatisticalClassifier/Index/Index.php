<?php

namespace Camspiers\StatisticalClassifier\Index;

use RuntimeException;

class Index implements IndexInterface
{
    protected $prepared = false;
    protected $data;
    protected $partitions = array();
    protected $temporaryPartitions = array();

    public function isPrepared()
    {
        return $this->prepared;
    }

    public function setPrepared($prepared)
    {
        $this->prepared = (boolean) $prepared;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getPartition($partitionName)
    {
        if (!isset($this->partitions[$partitionName])) {
            throw new RuntimeException(sprintf('The index partition \'%s\' does not exist', $partitionName));
        }

        return $this->partitions[$partitionName];
    }

    public function setPartition($partitionName, $partition, $temporary = true)
    {
        $this->partitions[$partitionName] = $partition;
        if ($temporary) {
            $this->temporaryPartitions[] = $partitionName;
        }
    }

    public function removePartition($partitionName)
    {
        if (isset($this->partitions[$partitionName])) {
            unset($this->partitions[$partitionName]);
        }
    }

    public function getTemporaryPartitions()
    {
        return $this->temporaryPartitions;
    }
}
