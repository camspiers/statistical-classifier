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
interface ModelInterface
{
    /**
     * Returns whether or not the model is prepared
     * @return boolean The prepared status
     */
    public function isPrepared();
    /**
     * @param $prepared
     * @return mixed
     */
    public function setPrepared($prepared);
    /**
     * Get the data
     * @return array
     */
    public function getModel();
    /**
     * @param $model
     * @return mixed
     */
    public function setModel($model);
}
