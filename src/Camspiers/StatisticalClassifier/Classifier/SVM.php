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

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\Model\SVMModel;
use Camspiers\StatisticalClassifier\Normalizer\Document;
use Camspiers\StatisticalClassifier\Normalizer\Token;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\Word;
use Camspiers\StatisticalClassifier\Transform;

/**
 * Provides a text based SVM classifier which uses libsvm
 *
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class SVM extends Classifier
{
    /**
     * Tokenizer (the way of breaking up documents)
     * @var TokenizerInterface
     */
    protected $tokenizer;
    /**
     * Takes document and makes it consistent
     * @var Document\NormalizerInterface
     */
    protected $documentNormalizer;
    /**
     * Takes tokenized data and makes it consistent or stem it
     * @var Token\NormalizerInterface
     */
    protected $tokenNormalizer;
    /**
     *
     * @var float|bool
     */
    protected $threshold;
    /**
     * @param DataSourceInterface          $dataSource
     * @param SVMModel                     $model
     * @param Document\NormalizerInterface $documentNormalizer
     * @param TokenizerInterface           $tokenizer
     * @param Token\NormalizerInterface    $tokenNormalizer
     * @param \SVM                         $svm
     * @param null                         $threshold
     */
    public function __construct(
        DataSourceInterface $dataSource,
        SVMModel $model = null,
        Document\NormalizerInterface $documentNormalizer = null,
        TokenizerInterface $tokenizer = null,
        Token\NormalizerInterface $tokenNormalizer = null,
        \SVM $svm = null,
        $threshold = null
    ) {
        $this->dataSource         = $dataSource;
        $this->model              = $model ? : new SVMModel();
        $this->documentNormalizer = $documentNormalizer ?: new Document\Lowercase();
        $this->tokenizer          = $tokenizer ?: new Word();
        $this->tokenNormalizer    = $tokenNormalizer;
        if (!$svm) {
            $svm = new \SVM();
            $svm->setOptions(
                array(
                    \SVM::OPT_KERNEL_TYPE => \SVM::KERNEL_LINEAR
                )
            );
        }
        $this->svm = $svm;
        if ($threshold) {
            $this->setThreshold($threshold);
        }
    }
    /**
     * @inheritdoc
     */
    public function prepareModel()
    {
        $data = $this->applyTransform(
            new Transform\TokenPreparation(
                $this->tokenizer,
                $this->documentNormalizer,
                $this->tokenNormalizer
            ),
            $this->dataSource->getData()
        );

        $tokenCountByDocument = $this->applyTransform(
            new Transform\TokenCountByDocument(),
            $data
        );

        $documentLength = $this->applyTransform(
            new Transform\DocumentLength(),
            $this->applyTransform(
                new Transform\TFIDF(),
                $tokenCountByDocument,
                $this->applyTransform(
                    new Transform\DocumentCount(),
                    $data
                ),
                $this->applyTransform(
                    new Transform\TokenAppearanceCount(),
                    $tokenCountByDocument
                )
            )
        );

        $categoryMap = array();
        $categoryCount = 0;
        $tokenMap = array();
        $tokenCount = 1;

        // Produce the token and category maps for the whole document set
        foreach ($documentLength as $category => $documents) {
            if (!array_key_exists($category, $categoryMap)) {
                $categoryMap[$category] = $categoryCount;
                $categoryCount++;
            }
            foreach ($documents as $document) {
                foreach (array_keys($document) as $token) {
                    if (!array_key_exists($token, $tokenMap)) {
                        $tokenMap[$token] = $tokenCount;
                        $tokenCount++;
                    }
                }
            }
        }

        // When using probabilities and our dataset is small we need to increase its
        // size by duplicating the data
        // see: http://www.csie.ntu.edu.tw/~cjlin/papers/libsvm.pdf section "8 Probability Estimates"
        if ($this->hasThreshold()) {
            foreach ($documentLength as $category => $documents) {
                while (count($documents) <= 5) {
                    foreach ($documents as $document) {
                        $documents[] = $document;
                    }
                }
                $documentLength[$category] = $documents;
            }
        }

        $transform = array();

        // Prep the svm data set for use
        foreach ($documentLength as $category => $documents) {
            foreach ($documents as $document) {
                $entry = array(
                    $categoryMap[$category]
                );
                foreach ($document as $token => $value) {
                    $entry[$tokenMap[$token]] = $value;
                }
                ksort($entry, SORT_NUMERIC);
                $transform[] = $entry;
            }
        }

        // Weight the data set by the number of docs that appear in each class.
        $weights = array();

        foreach ($documentLength as $category => $documents) {
            $weights[$categoryMap[$category]] = count($documents);
        }

        $lowest = min($weights);

        foreach ($weights as $index => $weight) {
            $weights[$index] = $lowest / $weight;
        }

        $this->model->setMaps(array_flip($categoryMap), $tokenMap);

        $this->model->setModel(
            $this->svm->train(
                $transform,
                $weights
            )
        );

        $this->model->setPrepared(true);
    }
    /**
     * @inheritdoc
     */
    public function classify($document)
    {
        /** @var SVMModel $model */
        $model = $this->preparedModel();

        $categoryMap = $model->getCategoryMap();

        $data = $this->prepareDocument($document, $model);

        if ($this->hasThreshold()) {
            $probabilities = array();
            $category = $model->getModel()->predict_probability($data, $probabilities);

            return $probabilities[$category] > $this->threshold ? $categoryMap[$category] : false;
        } else {
            $category = $model->getModel()->predict($data);

            return $categoryMap[$category];
        }
    }
    /**
     * Formats the document for use in \SVMModel
     * @param  string                                          $document
     * @param  \Camspiers\StatisticalClassifier\Model\SVMModel $model
     * @return array
     */
    protected function prepareDocument($document, SVMModel $model)
    {
        $tokenMap = $model->getTokenMap();

        $data = array();

        if ($this->documentNormalizer) {
            $document = $this->documentNormalizer->normalize($document);
        }

        $tokens = $this->tokenizer->tokenize($document);

        if ($this->tokenNormalizer) {
            $tokens = $this->tokenNormalizer->normalize($tokens);
        }

        $tokenCounts = array_count_values($tokens);

        foreach ($tokenCounts as $token => $value) {
            if (isset($tokenMap[$token])) {
                $data[$tokenMap[$token]] = $value;
            }
        }

        ksort($data, SORT_NUMERIC);

        return $data;
    }
    /**
     * Set the threshold probability a classifier document must meet
     * @param  float                     $threshold float value between 0-1
     * @throws \InvalidArgumentException
     */
    public function setThreshold($threshold)
    {
        if (is_numeric($threshold)) {
            $this->threshold = $threshold;
            $this->svm->setOptions(
                array(
                    \SVM::OPT_PROBABILITY => true
                )
            );
            if ($this->model->isPrepared()) {
                $this->model->setPrepared(false);
            }
        } else {
            throw new \InvalidArgumentException("Threshold must be a float value between 0-1");
        }
    }
    /**
     * Returns the probabilities of the document being in each category
     * @param  string $document
     * @return array
     */
    public function getProbabilities($document)
    {
        if ($this->hasThreshold()) {
            $model = $this->preparedModel();
            $data = $this->prepareDocument($document, $model);
            $probabilities = array();
            $model->getModel()->predict_probability($data, $probabilities);

            return array_combine($model->getCategoryMap(), $probabilities);
        }
    }
    /**
     * @return bool
     */
    protected function hasThreshold()
    {
        return $this->threshold !== null;
    }
}
