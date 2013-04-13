<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\DataSource;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Closure extends DataArray
{
    /**
     * @var callable
     */
    protected $closure;
    /**
     * @param callable $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        return $this->closure->__invoke();
    }
}
