<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Model;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Model implements ModelInterface
{
    /**
     * This is an status variable indicating that the nessacary processing
     * has occured on the model
     * @var boolean
     */
    protected $prepared = false;
    /**
     * The built model
     * @var array
     */
    protected $model = array();
    /**
     * @{inheritdoc}
     */
    public function isPrepared()
    {
        return $this->prepared;
    }
    /**
     * @param $prepared
     * @return mixed|void
     */
    public function setPrepared($prepared)
    {
        $this->prepared = $prepared;
    }
    /**
     * @param $model
     * @return mixed|void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
    /**
     * @return array
     */
    public function getModel()
    {
        return $this->model;
    }
}
