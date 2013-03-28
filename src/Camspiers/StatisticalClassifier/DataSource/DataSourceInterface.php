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
interface DataSourceInterface
{
    /**
     * Write the data source if possible
     * @return null
     */
    public function write();
    /**
     * Read the data source
     * @return array The data
     */
    public function read();
    /**
     * Get the data
     * @return array The data
     */
    public function getData();
    /**
     * Returns the categories of the data
     * @return array The categories
     */
    public function getCategories();
    /**
     * Returnnes whether or not the data has a category
     * @param  string  $category The category to check
     * @return boolean [description]
     */
    public function hasCategory($category);
    /**
     * Adds a category to the data
     * @param string $category The category to add
     */
    public function addCategory($category);
    /**
     * Adds a document by category to the data
     * @param string $category The category of the document
     * @param string $document The document
     */
    public function addDocument($category, $document);
}
