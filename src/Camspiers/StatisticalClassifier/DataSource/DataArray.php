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

use RuntimeException;
use Serializable;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class DataArray implements DataSourceInterface, Serializable
{
    protected $data = array();

    public function __construct($data = null)
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

    public function getDocumentCountByCategory($category)
    {
        return isset($this->data[$category]) ? count($this->data[$category]) : 0;
    }

    public function read()
    {
        return $this->data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function write()
    {
        throw new RuntimeException();
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
