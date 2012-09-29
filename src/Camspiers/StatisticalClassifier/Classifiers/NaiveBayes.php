<?php

namespace Camspiers\StatisticalClassifier\Classifiers;

use Camspiers\StatisticalClassifier\DataSource\DataSourceInterface;
use Camspiers\StatisticalClassifier\Tokenizers\TokenizerInterface;
use Camspiers\StatisticalClassifier\Stemmers\Porter;

class NaiveBayes implements ClassifierInterface
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
     * List of frequencies tokens appear in categories
     * @var array
     */
    private $frequencies = array();
    /**
     * List of likelihoods that a token appears in a document given that
     * the document is of a certain category
     * @var array
     */
    private $likelihoods = array();
    /**
     * The number of interesting tokens to consider
     * where interesting is defined by distance a posterior is from 0.5  
     * @var int
     */
    private $interestingTokenNumber = null;
    /**
     * Base prior probabilities of categories on training source data
     * @var boolean
     */
    private $dynamicPriors = true;
    /**
     * Exclude tokens found with frequency lower than this
     * @var int
     */
    private $tokenFrequencyThreshold = false;

    private $tokenFrequencies = array();
    private $tokenWeights = array();

    public function __construct(
        DataSourceInterface $source,
        TokenizerInterface $tokenizer,
        $interestingTokenNumber = null,
        $dynamicPriors = true,
        $tokenFrequencyThreshold = false
    )
    {
        $this->tokenizer = $tokenizer;
        $this->setSource($source);
        $this->interestingTokenNumber = $interestingTokenNumber;
        $this->dynamicPriors = $dynamicPriors;
        $this->tokenFrequencyThreshold = $tokenFrequencyThreshold;
    }

    /**
     * Handles calling "isCategoryName" where "CategoryName" depends on the
     * data source
     * @param  string $methodName The name of the method called
     * @param  array  $arguments  An array of arguments passed into the method
     * @return boolean|null       A boolean or null
     */
    public function __call($methodName, $arguments)
    {
        //First two chars of method name to check if is equivalent to "is"
        $pre = substr($methodName, 0, 2);
        //The potential name of the category
        $category = substr($methodName, 2);
        if (
            $pre == 'is'
            && array_key_exists($category, $this->tokenFrequencies)
            && count($arguments) == 1
        ) {
            //Return the posterior probability that the document
            //is of a certain category
            return $category == $this->classify($arguments[0]);
        }
    }

    public function classify($document)
    {
        $categoryResults = array();
        $tokens = array_count_values($this->tokenizer->tokenize($document));
        foreach ($this->getCategories() as $category) {
            $categoryResults[$category] = 0;
            foreach ($tokens as $token => $count) {
                if (array_key_exists($token, $this->tokenWeights[$category])) {
                    $categoryResults[$category] +=
                        $count * $this->tokenWeights[$category][$token];
                }
            }
        }
        asort($categoryResults, SORT_NUMERIC);
        reset($categoryResults);
        return key($categoryResults);
    }
    /**
     * Get the trained categories
     * @return array The trained categories
     */
    public function getCategories()
    {
        //Return all first level keys in the likelihoods array
        return array_keys($this->tokenFrequencies);
    }

    public function setSource(DataSourceInterface $source)
    {
        $this->source = $source;
        $this->calculate();
    }

    protected function calculate()
    {
        $this->calculateTokenFrequencies();
        $this->applyTransforms();
    }

    protected function calculateTokenFrequencies()
    {
        echo 'Calculate token frequencies', PHP_EOL;
        $data = $this->source->getData();
        echo 'Got data', PHP_EOL;
        foreach ($data as $category => $documents) {
            echo 'Calculating frequencies for ' , $category, PHP_EOL;
            foreach ($documents as $document) {
                echo '.';
                $this->updateTokenFrequencies($category, $document);
            }
            echo PHP_EOL;
        }
    }

    protected function updateTokenFrequencies($category, $document)
    {    
        if (!array_key_exists($category, $this->tokenFrequencies)) {
            $this->tokenFrequencies[$category] = array();
        }
        $this->tokenFrequencies[$category][] = array_count_values(
            $this->normalizeTokens(
                $this->tokenizer->tokenize(
                    $document
                )
            )
        );   
    }

    protected function applyTransforms()
    {
        $tokenCount = array();
        $documentCount = 0;

        foreach ($this->tokenFrequencies as $category => $documents) {
            $documentCount += count($documents);
            foreach ($documents as $document) {
                foreach ($document as $token => $count) {
                    if (!array_key_exists($token, $tokenCount)) {
                        $tokenCount[$token] = 0;
                    }
                    $tokenCount[$token]++;
                }
            }
        }

        //Apply term frequency transform
        echo 'Apply term frequency transform', PHP_EOL;
        foreach ($this->tokenFrequencies as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    $this->tokenFrequencies
                        [$category]
                        [$documentIndex]
                        [$token] = log($count + 1, 10);
                }
            }
        }

        //Apply document frequency transform
        echo 'Apply document frequency transform', PHP_EOL;
        foreach ($this->tokenFrequencies as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    $this->tokenFrequencies
                        [$category]
                        [$documentIndex]
                        [$token] = $count * log(
                            $documentCount / $tokenCount[$token],
                            10
                        );
                }
            }
        }

        $pow2 = function ($val) {
            return $val * $val;
        };

        //Apply document length transform
        echo 'Apply document length transform', PHP_EOL;
        foreach ($this->tokenFrequencies as $category => $documents) {
            foreach ($documents as $documentIndex => $document) {
                foreach ($document as $token => $count) {
                    $this->tokenFrequencies
                        [$category]
                        [$documentIndex]
                        [$token] = $count / sqrt(
                            array_sum(
                                array_map(
                                    $pow2,
                                    $document
                                )
                            )
                        );
                }
            }
        }

        //Calculate token weights
        echo 'Calculate token weights', PHP_EOL;
        $tokensByCategory = array();
        foreach ($this->tokenFrequencies as $category => $documents) {
            $tokensByCategory[$category] = array();
            foreach ($documents as $document) {
                $tokensByCategory[$category] = array_merge(
                    $tokensByCategory[$category],
                    array_keys($document)
                );
            }
        }

        $weights = array();
        echo 'Complement part', PHP_EOL;
        foreach ($tokensByCategory as $excludeCategory => $tokens) {
            $weights[$excludeCategory] = array();
            foreach ($tokens as $token) {
                $numerator = 0;
                $denominator = 0;
                foreach ($this->getCategories() as $category) {
                    if ($category != $excludeCategory) {
                        foreach ($this->tokenFrequencies[$category] as $documents) {
                            if (array_key_exists($token, $documents)) {
                                $numerator += $documents[$token];
                            }
                            $numerator += 1;
                            $denominator += array_sum($documents) + count($documents);
                        }

                    }
                }
                $weights[$excludeCategory][$token] = log($numerator / $denominator, 10);
            }
        }

        //Weight normalization
        echo 'Weight normalization', PHP_EOL;
        foreach ($weights as $category => $tokens) {
            $this->tokenWeights[$category] = array();
            foreach ($tokens as $token => $weight) {
                $this->tokenWeights[$category][$token] = $weight / array_sum($tokens);
            }
        }

    }

    protected function normalizeTokens($tokens)
    {
        return array_map(
            array($this, 'normalizeToken'),
            $tokens
        );
    }

    protected function normalizeToken($token)
    {
        return strtolower($token);
        return Porter::Stem(strtolower($token));
    }

    public function update($category, $document)
    {
        if ($this->source->addDocument($category, $document)) {
            $this->source->write();
            $this->updateFrequencies($category, $document);
            $this->calculateLikelihoods();
        }
    }

}