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

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
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
            $groupedData = array_merge_recursive($groupedData, $dataSource->getData());
        }

        return $groupedData;
    }

    public function write()
    {
    }
}
