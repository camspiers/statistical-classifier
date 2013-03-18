<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Command\Command;
use CacheCache\Cache;

use Camspiers\StatisticalClassifier\Index\CachedIndex;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
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
