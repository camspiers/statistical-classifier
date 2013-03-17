<?php

/*
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\Transform\TransformInterface;

interface ClassifierInterface
{
    public function is($category, $document);
    public function classify($document);
    public function prepareIndex();
    public function addTransform(TransformInterface $transform);
    public function setTransforms(array $transforms);
}
