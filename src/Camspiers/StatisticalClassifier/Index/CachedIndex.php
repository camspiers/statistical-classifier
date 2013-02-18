<?php

namespace Camspiers\StatisticalClassifier\Index;

use CacheCache\Cache;

class CachedIndex extends Index
{
    private $indexName;
    private $cache;

    public function __construct($indexName, Cache $cache)
    {
        $this->indexName = $indexName;
        $this->cache = $cache;
        $this->restore();
    }

    public function setPrepared($prepared)
    {
        parent::setPrepared($prepared);
        if ($prepared) {
            $this->preserve();
        }
    }

    protected function preserve()
    {
        $this->cache->set(
            $this->indexName,
            array(
                'prepared'   => $this->prepared,
                'data'       => $this->data,
                'partitions' => $this->partitions
            )
        );
    }

    protected function restore()
    {
        $data = $this->cache->get($this->indexName);
        if (is_array($data)) {
            $this->prepared = $data['prepared'];
            $this->data = $data['data'];
            $this->partitions = $data['partitions'];
        }
    }

}
