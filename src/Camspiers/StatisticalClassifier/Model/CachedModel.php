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

use CacheCache\Cache;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class CachedModel extends Model
{
    /**
     * The name of the model
     * @var string
     */
    private $modelName;
    /**
     * An instance of Cache
     * @var Cache
     */
    private $cache;
    /**
     * Create the CachedModel using the modelname, cache and datasource
     * @param string $modelName The name of the model
     * @param Cache  $cache     The cache to use
     */
    public function __construct(
        $modelName,
        Cache $cache
    ) {
        $this->modelName = $modelName;
        $this->cache = $cache;
        $data = $this->cache->get($this->modelName);
        if ($data !== null) {
            $this->prepared = true;
            $this->model = $data;
        }
    }
    /**
     * @param  array      $model
     * @return mixed|void
     */
    public function setModel($model)
    {
        $this->model = $model;
        $this->cache->set($this->modelName, $model);
    }
}
