<?php

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use CacheCache\Cache;

class CachedIndex extends Index
{
    private $indexName;
    private $cache;

    public function __construct(
        $indexName,
        Cache $cache,
        DataSourceInterface $dataSource = null
    ) {
        parent::__construct($dataSource);
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

    public function preserve()
    {
        $this->cache->set(
            $this->indexName,
            array(
                'prepared'   => $this->prepared,
                'dataSource' => $this->dataSource,
                'partitions' => $this->partitions
            )
        );
    }

    protected function restore()
    {
        $data = $this->cache->get($this->indexName);
        if (is_array($data)) {
            $this->prepared = $data['prepared'];
            $this->dataSource = $data['dataSource'];
            $this->partitions = $data['partitions'];
        }
    }
}
