# Statistical Classifier

[![Build Status](https://travis-ci.org/camspiers/statistical-classifier.png?branch=master)](https://travis-ci.org/camspiers/statistical-classifier)

**This project is a work in progress**

Currently this project is at [major version 0](http://semver.org/) so the public API should not be considered stable.

## What is statistical classification?

> In machine learning and statistics, classification is the problem of identifying to which of a set of categories (sub-populations) a new observation belongs, on the basis of a training set of data containing observations (or instances) whose category membership is known. - [Wikipedia - Statistical Classification](http://en.wikipedia.org/wiki/Statistical_classification)

This library provides a statistical classifier written entirely in PHP. The project is written with a focus on reuse and customaizability. Using dependancy injection and interfaces, all components can be easily swapped out for your own version, or entirely new classifiers can be built.

By default a Naive Bayes classifier is provided which performs well on the [20 Newsgroups Data Set](http://qwone.com/~jason/20Newsgroups/). This classifier was built using a paper *[Tackling the Poor Assumptions of Naive Bayes Text Classifiers](resources/Tackling the Poor Assumptions of Naive Bayes Text Classifiers.pdf?raw=true)* by Jason Rennie (PDF).

## Overview

A classifier is built using the following component types:

| Component | Interface | Description |
| --------- | --------- | ----------- |
| Generic Classifier | ClassifierInterface | Acts as a base that other components are added to to build a classifier |
| Data Source | DataSourceInterface | Multiple available, they provide the raw training data to the classifier |
| Index | IndexInterface | This stores the results of each transform and is eventually the thing that is cached |
| Normalizer | NormalizerInterface | Takes an array of words and makes them more consistent, for example, lowercase, porter stemmed |
| Tokenizer | TokenizerInterface | Breaks up a string into tokens |
| Transforms | TransformInterface | Manipulates the Index to produce data ready for a classification rule |
| Classification rule | ClassificationRuleInterface | Uses the index prepared by the transforms and the data source to classify a document |

## Installation (with composer)
### For your application

    $ composer requre camspiers/statistical-classifier:~0.2

### For command-line use

    $ composer create-project camspiers/statistical-classifier .
    $ ln -s $PWD/bin/classifier /usr/local/bin/classifier

## Dependancy injection (Symfony)

This library uses Symfony's dependancy injection component. A [container extension](http://symfony.com/doc/2.1/components/dependency_injection/compilation.html) is provided, and a container is also provided so if you aren't already using Symfony's dependancy injection component you can still take advantage of the default services provided.

## Usage
### From within external PHP code
#### Building your own

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier;
$c = new Classifier\NaiveBayes(
    new Index\Index(
        new DataSource\DataArray(
            array(
                'spam' => array(
                    'Some spam document',
                    'Another spam document'
                ),
                'ham' => array(
                    'Some ham document',
                    'Another ham document'
                )
            )
        )
    ),
    new Tokenizer\Word(),
    new Normalizer\Lowercase()
);
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

#### Using the service container provided

```php
<?php
// Ensure composer autoloader is required
$c = new StatisticalClassifierServiceContainer;
// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Index\Index;
// This sets the index to the soon created classifier using a synthetic symfony service
$c->set(
    'index.index',
    new Index(
        $source = new DataArray(
            array(
                'spam' => array(
                    'Some spam document'
                ),
                'ham' => array(
                    'Some ham document'
                )
            )
        )
    )
);
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Another ham document');
echo $c->get('classifier.naive_bayes')->classify("Some ham document"), PHP_EOL; //ham
```

### Command-line executable
#### Commands

*train:document*

```
Usage:
 train:document index category document

Arguments:
 index                 Name of index
 category              Which category this data is
 document              The document to train on
```

*train:directory*

```
Usage:
 train:directory [-i|--include[="..."]] index directory

Arguments:
 index                 Name of index
 directory             The directory to train on
```

*classify*

```
Usage:
 classify index document

Arguments:
 index                 Name of index
 document              The document to classify
```

#### Example

```bash
$ classifier train:document MyIndexName spam "This is some spam"
$ classifier train:document MyIndexName ham "This is some ham"
$ classifier classify MyIndexName "Some spam"
```

## Unit testing

    statistical-classifier/ $ composer install --dev
    statistical-classifier/ $ vendor/bin/phpunit

## Internals
### Classifiers

* Generic Classifier
* Naive Bayes Classifier

### Data Sources

* DataArray
* Directory
* Json
* PDO
* PDOQuery
* Serialized

### Index

* Index
* CachedIndex

### Normalizers

* Lowercase
* Porter
* Stopword

### Tokenizers

* Word

### Tranforms

* Complement
* DC
* DL
* DocumentTokenCounts
* DocumentTokenSums
* IDF
* Prune
* TAC
* TBC
* TCBD
* TF
* TFIDF
* TFThreaded
* TransformInterface
* Weight
* WeightNormalization

### Classification Rules

* NaiveBayes

