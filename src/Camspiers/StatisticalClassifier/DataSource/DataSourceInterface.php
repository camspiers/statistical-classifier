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
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
interface DataSourceInterface
{
    public function write();
    public function read();
    public function getData();
    public function getCategories();
    public function hasCategory($category);
    public function addCategory($category);
    public function addDocument($category, $document);
}
