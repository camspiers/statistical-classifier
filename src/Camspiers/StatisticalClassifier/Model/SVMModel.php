<?php

namespace Camspiers\StatisticalClassifier\Model;

class SVMModel extends Model
{
    /**
     * @var array
     */
    protected $categoryMap;
    /**
     * @var array
     */
    protected $tokenMap;
    /**
     * @param $categoryMap
     * @param $tokenMap
     */
    public function setMaps($categoryMap, $tokenMap)
    {
        $this->categoryMap = $categoryMap;
        $this->tokenMap = $tokenMap;
    }
    /**
     * @return array
     */
    public function getCategoryMap()
    {
        return $this->categoryMap;
    }
    /**
     * @return array
     */
    public function getTokenMap()
    {
        return $this->tokenMap;
    }
}
