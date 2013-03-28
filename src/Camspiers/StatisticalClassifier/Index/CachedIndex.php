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
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class CachedIndex extends Index
{
    /**
     * The name of the index
     * @var string
     */
    private $indexName;
    /**
     * An instance of Cache
     * @var Cache
     */
    private $cache;
    /**
     * Create the CachedIndex using the indexname, cache and datasource
     * @param string              $indexName  The name of the index
     * @param Cache               $cache      The cache to use
     * @param DataSourceInterface $dataSource The place to get the data from
     */
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
    /**
     * Set this index to prepared an preserve it if true
     *
     * This is an status variable indicating that the nessacary processing
     * has occured on the index
     * @param boolean $prepared The prepared status
     */
    public function setPrepared($prepared)
    {
        parent::setPrepared($prepared);
        if ($prepared) {
            $this->preserve();
        }
    }
    /**
     * Save the index to the cache
     * @return null
     */
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
    /**
     * Restore the index from the cache
     * @return null
     */
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
