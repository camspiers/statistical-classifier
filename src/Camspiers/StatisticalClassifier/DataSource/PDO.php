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
class PDO extends DataArray
{
    private $queries;

    public function __construct($queries = array())
    {
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
