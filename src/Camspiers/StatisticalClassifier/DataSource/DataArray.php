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

use Camspiers\StatisticalClassifier\Config\DataSourceConfiguration;
use RuntimeException;
use Serializable;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
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

    protected $config;
    protected $processor;

    /**
     * Creates the data array
     * @param array $data The initial data
     */
    public function __construct(array $data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
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
        foreach ($this->getData() as $document) {
            if ($document['category'] === $category) {
                return true;
            }
        }
        return false;
    }
    /**
     * @{inheritdoc}
     */
    public function addDocument($category, $document)
    {
        $this->data[] = array(
            'category' => $category,
            'document' => $document
        );
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
            $this->setData($this->read());
        }

        return $this->data;
    }
    /**
     * @{inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $this->getProcessor()->processConfiguration(
            $this->getConfig(),
            array(
                $data
            )
        );
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

    public function setConfig(DataSourceConfiguration $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        if (null === $this->config) {
            $this->setConfig(new DataSourceConfiguration());
        }
        return $this->config;
    }

    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getProcessor()
    {
        if (null === $this->processor) {
            $this->setProcessor(new Processor());
        }
        return $this->processor;
    }
}
