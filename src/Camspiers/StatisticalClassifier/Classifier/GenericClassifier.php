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

use Camspiers\StatisticalClassifier\ClassificationRule\ClassificationRuleInterface;
use Camspiers\StatisticalClassifier\Index\IndexInterface;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Transform\TransformInterface;
use RuntimeException;

/**
 * A generic classifier which can be used to built a classifier given a number of injected components
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class GenericClassifier implements ClassifierInterface
{
    /**
     * Tokenizer (the way of breaking up documents)
     * @var TokenizerInterface
     */
    protected $tokenizer;
    /**
     * Take tokenized data and make it consistent or stem it
     * @var NormalizerInterface
     */
    protected $normalizer;
    /**
     * The rule to use to classify a document
     * @var ClassificationRuleInterface
     */
    protected $classificationRule;
    /**
     * The index to apply the transforms to
     * @var IndexInterface
     */
    protected $index;
    /**
     * An array of Transforms implementing TransformInterface
     * @var array
     */
    protected $transforms = array();
    /**
     * Create the classifying using the numerous components passed in
     * @param IndexInterface              $index              An index to modify with transforms
     * @param ClassificationRuleInterface $classificationRule The rule to classify the document with
     * @param TokenizerInterface          $tokenizer          The tokenizer to break up the documents
     * @param NormalizerInterface         $normalizer         The normaizer to make tokens consistent
     * @param array                       $transforms         Ann array of transforms to modify the index
     */
    public function __construct(
        IndexInterface $index,
        ClassificationRuleInterface $classificationRule,
        TokenizerInterface $tokenizer,
        NormalizerInterface $normalizer,
        array $transforms = null
    ) {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->classificationRule = $classificationRule;
        $this->index = $index;
        if (null !== $transforms) {
            $this->setTransforms($transforms);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function is($category, $document)
    {
        if ($this->index->getDataSource()->hasCategory($category)) {
            return $this->classify($document) === $category;
        } else {
            throw new RuntimeException("The category '$category' doesn't exist");
        }
    }
    /**
     * {@inheritdoc}
     */
    public function setTransforms(array $transforms)
    {
        if (is_array($transforms)) {
            $this->transforms = array();
            foreach ($transforms as $transform) {
                $this->addTransform($transform);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getTransforms()
    {
        return $this->transforms;
    }
    /**
     * {@inheritdoc}
     */
    public function addTransform(TransformInterface $transform)
    {
        $this->transforms[] = $transform;
    }
    /**
     * Apply the transforms to the index
     * @return null
     */
    protected function applyTransforms()
    {
        if (is_array($this->transforms)) {
            foreach ($this->transforms as $transform) {
                $transform->apply($this->index);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function prepareIndex()
    {
        $this->applyTransforms();
        $this->index->setPrepared(true);
    }
    /**
     * Return an index which has been prepared for classification
     * @return IndexInterface
     */
    protected function preparedIndex()
    {
        if (!$this->index->isPrepared()) {
            $this->prepareIndex();
        }

        return $this->index;
    }
    /**
     * {@inheritdoc}
     */
    public function setIndex(IndexInterface $index)
    {
        $this->index = $index;
    }
    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        return $this->index;
    }
    /**
     * {@inheritdoc}
     */
    public function classify($document)
    {
        return $this->classificationRule->classify(
            $this->preparedIndex(),
            $this->normalizer->normalize(
                $this->tokenizer->tokenize(
                    $document
                )
            )
        );
    }
}
