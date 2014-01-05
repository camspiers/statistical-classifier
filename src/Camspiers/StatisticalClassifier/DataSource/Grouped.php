<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\DataSource;

use InvalidArgumentException;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Grouped extends DataArray
{
    /**
     * The data sources to use
     * @var array
     */
    protected $dataSources = array();
    /**
     * Create the object passing in the datasources as an array
     * @param  mixed                     $dataSources The data sources
     * @throws \InvalidArgumentException
     */
    public function __construct($dataSources = array())
    {
        if (!is_array($dataSources)) {
            $dataSources = func_get_args();
        }

        if (count($dataSources) < 2) {
            throw new InvalidArgumentException("A group of data sources must contain at least 2 data sources");
        }

        foreach ($dataSources as $dataSource) {
            $this->addDataSource($dataSource);
        }
    }
    /**
     * Add a data source to the array
     * @param DataSourceInterface $dataSource The data source
     */
    public function addDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSources[] = $dataSource;
    }
    /**
     * Returns any datasources that are part of the group
     * @return array
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        $groupedData = array();

        foreach ($this->dataSources as $dataSource) {
            $groupedData[] = $dataSource->getData();
        }

        return call_user_func_array('array_merge', $groupedData);
    }
}
