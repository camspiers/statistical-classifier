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
        return array_keys($this->data);
    }
    /**
     * @{inheritdoc}
     */
    public function hasCategory($category)
    {
        $this->prepare();

        return isset($this->data[$category]);
    }
    /**
     * @{inheritdoc}
     */
    public function addDocument($category, $document)
    {
        if (!isset($this->data[$category])) {
            $this->data[$category] = array();
        }
        $this->data[$category][] = $document;
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
        $this->prepare();

        return $this->data;
    }
    /**
     * @{inheritdoc}
     */
    public function setData(array $data)
    {
        $data = $this->getProcessor()->processConfiguration(
            $this->getConfig(),
            array(
                $data
            )
        );
        foreach ($data as $document) {
            $this->addDocument($document['category'], $document['document']);
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
    /**
     * Read the data and set it
     */
    protected function prepare()
    {
        if (!is_array($this->data) || count($this->data) == 0) {
            $this->setData($this->read());
        }
    }
}
