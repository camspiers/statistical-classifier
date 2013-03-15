<?php

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Command\Command;
use CacheCache\Cache;

use Camspiers\StatisticalClassifier\Index\CachedIndex;

abstract class CacheableCommand extends Command
{
    protected $cache;

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    protected function getCachedIndex($name)
    {
        return new CachedIndex(
            $name,
            $this->cache
        );
    }
}
