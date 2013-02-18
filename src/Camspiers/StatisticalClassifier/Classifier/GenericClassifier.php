<?php

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Transform\TransformInterface;
use Camspiers\StatisticalClassifier\ClassificationRule\ClassificationRuleInterface;
use Camspiers\StatisticalClassifier\Index\IndexInterface;

class GenericClassifier implements ClassifierInterface
{
    /**
     * Source of training data
     * @var DataSourceInterface
     */
    private $source;
    /**
     * Tokenizer (the way of breaking up documents)
     * @var TokenizerInterface
     */
    private $tokenizer;
    /**
     * Take tokenized data and make it consistent or stem it
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * The rule to use to classify a document
     * @var ClassificationRule
     */
    private $classificationRule;
    /**
     * [$index description]
     * @var [type]
     */
    private $index;
    /**
     * An array of Transforms implementing TransformInterface
     * @var array
     */
    private $transforms = array();

    public function __construct(
        DataSourceInterface $source,
        IndexInterface $index,
        ClassificationRuleInterface $classificationRule,
        TokenizerInterface $tokenizer,
        NormalizerInterface $normalizer,
        array $transforms = null
    )
    {
        $this->source = $source;
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
        $this->classificationRule = $classificationRule;
        $this->index = $index;
        if (null !== $transforms) {
            $this->setTransforms($transforms);
        }
    }

    public function setTransforms($transforms)
    {
        if (is_array($transforms)) {
            $this->transforms = array();
            foreach ($transforms as $transform) {
                $this->addTransform($transform);
            }
        }
    }

    public function addTransform(TransformInterface $transform)
    {
        $this->transforms[] = $transform;
    }

    protected function applyTransforms()
    {
        if (is_array($this->transforms)) {
            foreach ($this->transforms as $transform) {
                $transform->apply($this->index);
            }
        }
    }

    protected function preparedIndex()
    {
        if (!$this->index->isPrepared()) {
            $this->index->setData($this->source->getData());
            $this->applyTransforms();
            $this->index->setPrepared(true);
        }

        return $this->index;
    }

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
