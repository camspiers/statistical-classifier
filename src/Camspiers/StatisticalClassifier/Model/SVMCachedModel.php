<?php

namespace Camspiers\StatisticalClassifier\Model;

use CacheCache\Cache;

class SVMCachedModel extends SVMModel
{
    /**
     * @param Cache $cache
     * @param       $modelFilename
     */
    public function __construct($modelFilename, Cache $cache)
    {
        $this->modelFilename = $modelFilename;
        $this->cache = $cache;
        $data = $this->cache->get($modelFilename);
        if ($data !== null && file_exists($this->modelFilename)) {
            $this->model = new \SVMModel;
            $this->model->load($this->modelFilename);
            $this->categoryMap = $data['categoryMap'];
            $this->tokenMap = $data['tokenMap'];
            $this->prepared = true;
        }
    }
    /**
     * @param $model
     * @return mixed|void
     * @throws \RuntimeException
     */
    public function setModel($model)
    {
        if (!$model instanceof \SVMModel) {
            throw new \RuntimeException("Expected SVMModel");
        }
        $this->model = $model;
        $this->model->save($this->modelFilename);
    }
    /**
     * @param $categoryMap
     * @param $tokenMap
     */
    public function setMaps($categoryMap, $tokenMap)
    {
        $this->cache->set(
            $this->modelFilename,
            array(
                'categoryMap' => $categoryMap,
                'tokenMap' => $tokenMap
            )
        );
        parent::setMaps($categoryMap, $tokenMap);
    }
}
