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

use Camspiers\StatisticalClassifier\ClassificationRule;
use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\Model\SVMModel;
use Camspiers\StatisticalClassifier\Normalizer\Lowercase;
use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\Word;
use Camspiers\StatisticalClassifier\Transform;

/**
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
     * Take tokenized data and make it consistent or stem it
     * @var NormalizerInterface
     */
    protected $normalizer;
    /**
     *
     * @var float|bool
     */
    protected $threshold;
    /**
     * @param DataSourceInterface $dataSource
     * @param SVMModel            $model
     * @param TokenizerInterface  $tokenizer
     * @param NormalizerInterface $normalizer
     * @param \SVM                $svm
     * @param null                $threshold
     */
    public function __construct(
        DataSourceInterface $dataSource,
        SVMModel $model = null,
        TokenizerInterface $tokenizer = null,
        NormalizerInterface $normalizer = null,
        \SVM $svm = null,
        $threshold = null
    ) {
        $this->dataSource = $dataSource;
        $this->model = $model ? : new SVMModel();
        $this->tokenizer = $tokenizer ? : new Word();
        $this->normalizer = $normalizer ? : new Lowercase();
        if (!$svm) {
            $svm = new \SVM();
            $svm->setOptions(
                array(
                    \SVM::OPT_KERNEL_TYPE => \SVM::KERNEL_LINEAR
                )
            );
        }
        $this->svm = $svm;
        $this->setThreshold($threshold);
    }
    /**
     * {@inheritdoc}
     */
    public function prepareModel()
    {
        $data = $this->dataSource->getData();

        $tokenCountByDocument = $this->applyTransform(
            new Transform\TokenCountByDocument(
                $this->tokenizer,
                $this->normalizer
            ),
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
     * @param string $document
     * @return mixed|string
     */
    public function classify($document)
    {
        /** @var SVMModel $model */
        $model = $this->preparedModel();

        $categoryMap = $model->getCategoryMap();

        $data = $this->prepareData($document, $model);

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
     * @param $document
     * @param $model
     * @return array
     */
    protected function prepareData($document, SVMModel $model)
    {
        $tokenMap = $model->getTokenMap();

        $data = array();

        $tokenCounts = array_count_values(
            $this->normalizer->normalize(
                $this->tokenizer->tokenize(
                    $document
                )
            )
        );

        foreach ($tokenCounts as $token => $value) {
            if (isset($tokenMap[$token])) {
                $data[$tokenMap[$token]] = $value;
            }
        }

        ksort($data, SORT_NUMERIC);

        return $data;
    }
    /**
     * @param $threshold
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
        }
    }
    /**
     * @param $document
     * @return array
     */
    public function getProbabilities($document)
    {
        if ($this->hasThreshold()) {
            $model = $this->preparedModel();
            $data = $this->prepareData($document, $model);
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
