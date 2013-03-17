<?php

namespace Camspiers\StatisticalClassifier\Classifier;

use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Transform\TransformInterface;
use Camspiers\StatisticalClassifier\ClassificationRule\ClassificationRuleInterface;
use Camspiers\StatisticalClassifier\Index\IndexInterface;

use RuntimeException;

class GenericClassifier implements ClassifierInterface
{
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

    public function is($category, $document)
    {
        if ($this->index->getDataSource()->hasCategory($category)) {
            return $this->classify($document) === $category;
        } else {
            throw new RuntimeException("The category '$category' doesn't exist");
        }
    }

    public function setTransforms(array $transforms)
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

    public function prepareIndex()
    {
        $this->applyTransforms();
        $this->index->setPrepared(true);
    }

    protected function preparedIndex()
    {
        if (!$this->index->isPrepared()) {
            $this->prepareIndex();
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
