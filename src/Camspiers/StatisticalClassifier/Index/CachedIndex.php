<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Index;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use CacheCache\Cache;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
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
