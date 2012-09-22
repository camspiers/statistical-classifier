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
    /**
     * Make training data have the same size for each category
     * @var boolean
     */
    private $normalizeSourceSize = true;

    public function __construct(
        DataSourceInterface $source,
        TokenizerInterface $tokenizer,
        $interestingTokenNumber = null,
        $dynamicPriors = true,
        $tokenFrequencyThreshold = false,
        $normalizeSourceSize = true
    )
    {
        $this->tokenizer = $tokenizer;
        $this->setSource($source);
        $this->interestingTokenNumber = $interestingTokenNumber;
        $this->dynamicPriors = $dynamicPriors;
        $this->tokenFrequencyThreshold = $tokenFrequencyThreshold;
        $this->normalizeSourceSize = $normalizeSourceSize;
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
            && array_key_exists($category, $this->likelihoods)
            && count($arguments) == 2
        ) {
            //Return the posterior probability that the document
            //is of a certain category
            return $this->posteriorProbability(
                $category,
                $this->tokenizer->tokenize($arguments[1])
            ) >= $arguments[0];
        }
    }
    /**
     * A general classification method which returns the
     * best classification for a document
     * @param  string $document The document to clasify
     * @return string           The classification
     */
    public function classify($document)
    {
        //An array to store the computed probabilities
        $probs = array();
        //A tokenized document
        $tokens = $this->tokenizer->tokenize($document);
        //Loop over all categories
        foreach ($this->getCategories() as $category) {
            //Store the posterior probability that the document
            //is classified in this category given all the tokens
            $probs[$category] = $this->posteriorProbability(
                $category,
                $tokens
            );
        }
        //Sort the probs with the highest at the start of the array
        arsort($probs, SORT_NUMERIC);
        //Make sure our index is at the beginning of the array
        reset($probs);
        //Return the classification
        return key($probs);
    }

    public function getCategories()
    {
        //Return all first level keys in the likelihoods array
        return array_keys($this->likelihoods);
    }

    public function getPriors($categories)
    {
        //Set up an array to hold priors for each category
        $priors = array();

        //Loop over each category storing the prior for each
        foreach ($categories as $category) {
            $priors[$category] = $this->dynamicPriors
                ? $this->getPrior($category)
                : 1 / count($categories);
        }

        return $priors;
    }

    public function getPrior($category)
    {
        //Prior is the number of documents in the category divided by the
        //number of documents in our source. This assumption only holds in
        //cases where our source is representative of the actual data
        return $this->source->categoryCount(
            $category
        ) / $this->source->documentCount();
    }

    public function posteriorProbability($category, $tokens)
    {
        //Set up an array to store posteriors to be combined
        $probs = array();
        //Get all categories
        $categories = $this->getCategories();
        //Get priors for each category
        $priors = $this->getPriors($categories);
        //Remove the category currently being tested
        $categories = array_diff($categories, array($category));
        //Normalize tokens
        $tokens = array_unique(
            array_map(
                array($this, 'normalizeToken'),
                $tokens
            )
        );

        //Get a posterior probability for each token in the document
        foreach ($tokens as $token) {
            //Don't count skip tokens
            if (!$this->isSkipToken($token)) {
                //Calculate the numerator of bayes' theorem
                $num = $priors[$category] * $this->getLikelihood(
                    $category,
                    $token
                );
                //Denominator the sum of P(CATEGORY_i) * P(TOKEN|CATEGORY_i) for all i
                $denom = $num;
                foreach ($categories as $denomCategory) {
                    $denom += $priors[$denomCategory] * $this->getLikelihood(
                        $denomCategory,
                        $token
                    );
                }
                //Probability is the numerator / denominator by bayes' theorem
                //P(CATEGORY) * P(TOKEN|CATEGORY) / sum( P(CATEGORY_i) * P(TOKEN|CATEGORY_i) )
                $probs[$token] = $num / $denom;
            }
        }

        //If the number of tokens considered is more that the interesting token
        //number then return only the probabilities of the most interesting
        //tokens
        if (
            !is_null($this->interestingTokenNumber)
            && is_int($this->interestingTokenNumber)
            && count($probs) > $this->interestingTokenNumber
        ) {

            //Produce an array of items with the largest items being the ones
            //Furthest away from 0.5
            $interesting = array_map(function ($prob) {
                return abs(0.5 - $prob);
            }, $probs);

            //Sort the items so the largest is first
            arsort($interesting);

            //The probabilities for the most interesting tokens
            $probs = array_intersect_key(
                $probs,
                array_slice(
                    $interesting,
                    0,
                    $this->interestingTokenNumber
                )
            );
        }

        //Return 1 / 1 + e^(sum(ln(1 - p_i) - ln(p_i)))
        //http://en.wikipedia.org/wiki/Bayesian_spam_filtering#Other_expression_of_the_formula_for_combining_individual_probabilities
        //Used to avoid floating-point underflow
        return 1 / (1 + exp(array_sum(array_map(function ($prob) {
            return log(1 - $prob) - log($prob);
        }, $probs))));
    }

    public function averagePriors($categories)
    {
        return array_sum($this->getPriors($categories)) / count($categories);
    }

    public function hasLikelihood($category, $token)
    {
        return array_key_exists($category, $this->likelihoods)
            && array_key_exists($token, $this->likelihoods[$category]);
    }

    public function getLikelihood($category, $token)
    {
        if ($this->hasLikelihood($category, $token)) {
            return $this->likelihoods[$category][$token];
        }

        $likelihoods = array();

        foreach (array_diff($this->getCategories(), array($category)) as $category) {
            if ($this->hasLikelihood($category, $token)) {
                $likelihoods[$category] = $this->getLikelihood($category, $token);
            }
        }

        //1 / $this->getPrior($category)
        //TODO: 
        return count($likelihoods) > 0
            ? $this->averagePriors(array_keys($likelihoods))
                * (array_sum($likelihoods) / (count($likelihoods)))
            : 0.000000000000001;
    }

    public function setSource(DataSourceInterface $source)
    {
        $this->source = $source;
        $this->calculate();
    }

    protected function calculate()
    {
        $this->calculateFrequencies();
        $this->calculateLikelihoods();
    }

    protected function calculateFrequencies()
    {
        $data = $this->source->getData($this->normalizeSourceSize);
        foreach ($data as $category => $documents) {
            foreach ($documents as $document) {
                $this->updateFrequencies($category, $document);
            }
        }
    }

    protected function calculateLikelihoods()
    {
        foreach ($this->frequencies as $category => $data) {
            if (!array_key_exists($category, $this->likelihoods)) {
                $this->likelihoods[$category] = array();
            }
            $documentCount = $this->source->categoryCount($category);
            foreach ($data as $token => $count) {
                if (!$this->tokenFrequencyThreshold || $count >= $this->tokenFrequencyThreshold) {
                    $this->likelihoods[$category][$token] = $count / $documentCount;
                }
            }
        }
    }

    protected function updateFrequencies($category, $document)
    {        
        if (!array_key_exists($category, $this->frequencies)) {
            $this->frequencies[$category] = array();
        }
        $tokens = array_unique(
            array_map(
                array($this, 'normalizeToken'),
                $this->tokenizer->tokenize($document)
            )
        );
        foreach ($tokens as $token) {
            if (!array_key_exists($token, $this->frequencies[$category])) {
                $this->frequencies[$category][$token] = 0;
            }
            $this->frequencies[$category][$token]++;
        }
    }

    protected function normalizeToken($token)
    {
        // return strtolower($token);
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

    public function isSkipToken($token)
    {
        return in_array($token, array(
            'a', 'about', 'above', 'across', 'after', 'afterwards', 
            'again', 'against', 'all', 'almost', 'alone', 'along', 
            'already', 'also', 'although', 'always', 'am', 'among', 
            'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 
            'any', 'anyhow', 'anyone', 'anything', 'anyway', 'anywhere', 
            'are', 'around', 'as', 'at', 'back', 'be', 
            'became', 'because', 'become', 'becomes', 'becoming', 'been', 
            'before', 'beforehand', 'behind', 'being', 'below', 'beside', 
            'besides', 'between', 'beyond', 'bill', 'both', 'bottom', 
            'but', 'by', 'call', 'can', 'cannot', 'cant', 'dont',
            'co', 'computer', 'con', 'could', 'couldnt', 'cry', 
            'de', 'describe', 'detail', 'do', 'done', 'down', 
            'due', 'during', 'each', 'eg', 'eight', 'either', 
            'eleven', 'else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 
            'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 
            'fify', 'fill', 'find', 'fire', 'first', 'five', 
            'for', 'former', 'formerly', 'forty', 'found', 'four', 
            'from', 'front', 'full', 'further', 'get', 'give', 
            'go', 'had', 'has', 'hasnt', 'have', 'he', 
            'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 
            'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 
            'how', 'however', 'hundred', 'i', 'ie', 'if', 
            'in', 'inc', 'indeed', 'interest', 'into', 'is', 
            'it', 'its', 'itself', 'keep', 'last', 'latter', 
            'latterly', 'least', 'less', 'ltd', 'made', 'many', 
            'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 
            'more', 'moreover', 'most', 'mostly', 'move', 'much', 
            'must', 'my', 'myself', 'name', 'namely', 'neither', 
            'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 
            'none', 'noone', 'nor', 'not', 'nothing', 'now', 
            'nowhere', 'of', 'off', 'often', 'on', 'once', 
            'one', 'only', 'onto', 'or', 'other', 'others', 
            'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 
            'own', 'part', 'per', 'perhaps', 'please', 'put', 
            'rather', 're', 'same', 'see', 'seem', 'seemed', 
            'seeming', 'seems', 'serious', 'several', 'she', 'should', 
            'show', 'side', 'since', 'sincere', 'six', 'sixty', 
            'so', 'some', 'somehow', 'someone', 'something', 'sometime', 
            'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 
            'ten', 'than', 'that', 'the', 'their', 'them', 
            'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 
            'therefore', 'therein', 'thereupon', 'these', 'they', 'thick', 
            'thin', 'third', 'this', 'those', 'though', 'three', 
            'through', 'throughout', 'thru', 'thus', 'to', 'together', 
            'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 
            'two', 'un', 'under', 'until', 'up', 'upon', 
            'us', 'very', 'via', 'was', 'we', 'well', 
            'were', 'what', 'whatever', 'when', 'whence', 'whenever', 
            'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 
            'wherever', 'whether', 'which', 'while', 'whither', 'who', 
            'whoever', 'whole', 'whom', 'whose', 'why', 'will', 
            'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 
            'yourself', 'yourselves'
        ));
    }

}
