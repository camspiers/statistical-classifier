<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use RuntimeException;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Index implements IndexInterface
{
    /**
     * This is an status variable indicating that the nessacary processing
     * has occured on the index
     * @var boolean
     */
    protected $prepared = false;
    /**
     * Contains the data from which to build the index from
     * @var DataSourceInterface
     */
    protected $dataSource;
    /**
     * Holds various data by key
     * @var array
     */
    protected $partitions = array();
    /**
     * Create the Index using a data source
     * @param DataSourceInterface $dataSource The data source
     */
    public function __construct(DataSourceInterface $dataSource = null)
    {
        $this->dataSource = $dataSource instanceof DataSourceInterface ? $dataSource : new DataArray();
    }
    /**
     * @{inheritdoc}
     */
    public function isPrepared()
    {
        return $this->prepared;
    }
    /**
     * @{inheritdoc}
     */
    public function setPrepared($prepared)
    {
        $this->prepared = (boolean) $prepared;
    }
    /**
     * @{inheritdoc}
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }
    /**
     * @{inheritdoc}
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
    /**
     * @{inheritdoc}
     */
    public function getPartition($partitionName)
    {
        if (!isset($this->partitions[$partitionName])) {
            throw new RuntimeException(sprintf('The index partition \'%s\' does not exist', $partitionName));
        }

        return $this->partitions[$partitionName];
    }
    /**
     * @{inheritdoc}
     */
    public function setPartition($partitionName, $partition)
    {
        $this->partitions[$partitionName] = $partition;
    }
    /**
     * @{inheritdoc}
     */
    public function removePartition($partitionName)
    {
        if (isset($this->partitions[$partitionName])) {
            unset($this->partitions[$partitionName]);
        }
    }
    /**
     * @{inheritdoc}
     */
    public function getPartitions()
    {
        return array_keys($this->partitions);
    }
}
