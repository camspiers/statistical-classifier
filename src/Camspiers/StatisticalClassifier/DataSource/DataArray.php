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
     *     array(
     *         'category' => 'somecategory',
     *         'document' => 'Some document'
     *     )
     * )
     * @var array
     */
    protected $data = array();
    /**
     * @var array
     */
    protected $categories = array();
    /**
     * Holds the config class that setData needs to conforms to
     * @var
     */
    protected $config;
    /**
     * Processes the data with the config
     * @var
     */
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
        return $this->categories;
    }
    /**
     * @{inheritdoc}
     */
    public function hasCategory($category)
    {
        return in_array($category, $this->categories);
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
    protected function read()
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
        foreach ($this->data as $document) {
            if (!in_array($document['category'], $this->categories)) {
                $this->categories[] = $document['category'];
            }
        }
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
    /**
     * @param DataSourceConfiguration $config
     */
    public function setConfig(DataSourceConfiguration $config)
    {
        $this->config = $config;
    }
    /**
     * Return the config for the data
     * @return mixed
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->setConfig(new DataSourceConfiguration());
        }

        return $this->config;
    }
    /**
     * Sets the processor for the config
     * @param Processor $processor
     */
    protected function setProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * Gets the processor
     * @return mixed
     */
    protected function getProcessor()
    {
        if (null === $this->processor) {
            $this->setProcessor(new Processor());
        }

        return $this->processor;
    }
}
