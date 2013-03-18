<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\ClassificationRule;

use Camspiers\StatisticalClassifier\Index\IndexInterface;

/**
 * Provides an interface for classification rule.
 * Classes of this type are injected into the constructor of a classifier
 *
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
interface ClassificationRuleInterface
{
    /**
     * Classifies a document against an index
     * @param  IndexInterface $index    The Index to classify against
     * @param  string         $document The document to classify
     * @return string         The category of the document
     */
    public function classify(IndexInterface $index, $document);
}
