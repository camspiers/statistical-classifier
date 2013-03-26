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
    /**
     * An array to hold the sources data
     *
     * Should be in the form:
     * array(
     *     'category' => array(
     *         'Some document'
     *     )
     * )
     * @var array
     */
    protected $data = array();

    /**
     * Creates the data array
     * @param array $data The initial data
     */
    public function __construct(array $data = null)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
    }
    /**
     * @{inheritdoc}
     */
    public function getCategories()
    {
        return array_keys($this->getData());
    }
    /**
     * @{inheritdoc}
     */
    public function hasCategory($category)
    {
        return array_key_exists($category, $this->getData());
    }
    /**
     * @{inheritdoc}
     */
    public function addCategory($category)
    {
        $this->data[$category] = array();
    }
    /**
     * @{inheritdoc}
     */
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
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        return $this->data;
    }
    /**
     * @{inheritdoc}
     */
    public function getData()
    {
        if (!is_array($this->data) || count($this->data) == 0) {
            $this->data = $this->read();
        }

        return $this->data;
    }
    /**
     * @{inheritdoc}
     */
    public function write()
    {
        throw new RuntimeException('This data source cannot be written');
    }
    /**
     * Serialize the class
     * @return string The serialized data
     */
    public function serialize()
    {
        return serialize($this->getData());
    }
    /**
     * Restore the serialized class
     * @param  string $data The serialized data
     * @return null
     */
    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
