<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\Transform\TransformInterface;

/**
 * Provides an interface for classifier.
 * Implementing classes are classifiers capable of classifying documents into categories
 *
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
interface ClassifierInterface
{
    /**
     * Returns whether or not the document is of the category
     * @param  string  $category The category in question
     * @param  string  $document The document to check
     * @return boolean Whether or not the document is in the category
     */
    public function is($category, $document);
    /**
     * Classify the document and return its category
     * @param  string $document The document to classify
     * @return string The category of the document
     */
    public function classify($document);
    /**
     * Builds the index from the data source by applying transforms to the data source
     * @return null
     */
    public function prepareIndex();
    /**
     * Add a transform to be applied to the source data to achieve classification
     * @param  TransformInterface $transform The transform to add
     * @return null
     */
    public function addTransform(TransformInterface $transform);
    /**
     * Set an array of transform to be applied to the source data to achieve classification
     * @param  array $transforms The transforms to add
     * @return null
     */
    public function setTransforms(array $transforms);
}
