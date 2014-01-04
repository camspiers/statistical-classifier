<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\Model\ModelInterface;
use RuntimeException;

/**
 * A generic classifier which can be used to built a classifier given a number of injected components
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Classifier implements ClassifierInterface
{
    /**
     * @var \Camspiers\StatisticalClassifier\DataSource\DataSourceInterface
     */
    protected $dataSource;
    /**
     * The model to apply the transforms to
     * @var \Camspiers\StatisticalClassifier\Model\ModelInterface
     */
    protected $model;
    /**
     * @inheritdoc
     */
    public function is($category, $document)
    {
        if ($this->dataSource->hasCategory($category)) {
            return $this->classify($document) === $category;
        } else {
            throw new RuntimeException(
                sprintf(
                    "The category '%s' doesn't exist",
                    $category
                )
            );
        }
    }
    /**
     * Builds the model from the data source by applying transforms to the data source
     * @return null
     */
    abstract public function prepareModel();
    /**
     * Return an model which has been prepared for classification
     * @return \Camspiers\StatisticalClassifier\Model\ModelInterface
     */
    protected function preparedModel()
    {
        if (!$this->model->isPrepared()) {
            $this->prepareModel();
        }

        return $this->model;
    }
    /**
     * Take a callable and run it passing in any additionally specified arguments
     * @param  callable          $transform
     * @throws \RuntimeException
     * @return mixed
     */
    protected function applyTransform($transform)
    {
        if (is_callable($transform)) {
            return call_user_func_array($transform, array_slice(func_get_args(), 1));
        } else {
            throw new RuntimeException("Argument to applyTransform must be callable");
        }
    }
    /**
     * @param \Camspiers\StatisticalClassifier\Model\ModelInterface $model
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
    }
    /**
     * @param \Camspiers\StatisticalClassifier\DataSource\DataSourceInterface $dataSource
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }
}
