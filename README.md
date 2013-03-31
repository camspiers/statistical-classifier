# PHP Statistical Classifier

[![Build Status](https://travis-ci.org/camspiers/statistical-classifier.png?branch=master)](https://travis-ci.org/camspiers/statistical-classifier)

NOTE: PHP Statistical Classifier uses [semantic versioning](http://semver.org/), it is currently at major version 0, so the public API should not be considered stable.

# What is it?

> In machine learning and statistics, classification is the problem of identifying to which of a set of categories (sub-populations) a new observation belongs, on the basis of a training set of data containing observations (or instances) whose category membership is known. - [Wikipedia - Statistical Classification](http://en.wikipedia.org/wiki/Statistical_classification)

PHP Statistical Classifier is written entirely in PHP, with a focus on reuse and customizability, allowed by dependancy injection and interfaces.

Important Features:

* Highly customizable
* Multiple ways of using the library (PHP, command-line, HTTP server)
* Support for use from non-PHP programming languages (through command-line or HTTP server)
* Multiple **data import types** to get your data into the classifier (Directory of files, Database queries, Json, Serialized arrays)
* Multiple **types of caching** for the index the classifier builds (Memcache, Apc, File, Session)
* Multiple ways to make your input data more consistent (Lowercase, Porter Stemmer)
* Faster setup time in applications using Symfony Dependency Injection

PHP Classifier is also built with a structure that allows developers to easily implement their own classifiers, even reusing the underlying algorithms between classifiers.

By default a Naive Bayes classifier is provided which performs well on the [20 Newsgroups Data Set](http://qwone.com/~jason/20Newsgroups/). This classifier was built using a paper *[Tackling the Poor Assumptions of Naive Bayes Text Classifiers](resources/Tackling the Poor Assumptions of Naive Bayes Text Classifiers.pdf)* by Jason Rennie (PDF).

# Does it work?

Classifiers are extensively used in combating spam. Having your own classifier allows you to have a classifier that is trained on the type of spam and ham your site receives, meaning it will be more accurate and allow you to not have dropoffs through using captcha methods.

Classifiers are also used in automatically categorizing site content (pages, articles, news etc.).

# Installation

## For your PHP application

```bash
$ composer requre camspiers/statistical-classifier:~0.3
```

## For command-line use

```bash
$ composer create-project camspiers/statistical-classifier .
$ ln -s $PWD/bin/classifier /usr/local/bin/classifier
```

## Manual (not recommended)

```bash
$ curl -LO https://github.com/camspiers/statistical-classifier/archive/master.zip
$ unzip master.zip -d statistical-classifier
$ composer install -d statistical-classifier
```

# Usage

## Without Symfony Dependency Injection

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier;
$c = new Classifier\NaiveBayes(
    new Index\Index(
        new DataSource\DataArray(
            array(
                array(
                    'category' => 'spam',
                    'document' => 'Some spam document'
                ),
                array(
                    'category' => 'spam',
                    'document' => 'Another spam document'
                ),
                array(
                    'category' => 'ham',
                    'document' => 'Some ham document'
                ),
                array(
                    'category' => 'ham',
                    'document' => 'Another ham document'
                )
            )
        )
    ),
    new Tokenizer\Word(),
    new Normalizer\Lowercase()
);
$c->is('ham', 'Some ham document'); // true
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

## With Symfony Dependency Injection

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
                array(
                    'category' => 'spam',
                    'document' => 'Some spam document'
                ),
                array(
                    'category' => 'spam',
                    'document' => 'Another spam document'
                ),
                array(
                    'category' => 'ham',
                    'document' => 'Some ham document'
                ),
                array(
                    'category' => 'ham',
                    'document' => 'Another ham document'
                )
            )
        )
    )
);
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Another ham document');
echo $c->get('classifier.complement_naive_bayes')->classify("Some ham document"), PHP_EOL; //ham
```

## From command-line

### Commands

*train:document*

```
Usage:
 train:document [-c|--classifier[="..."]] [-p|--prepare] index category document

Arguments:
 index                 Name of index
 category              Which category this data is
 document              The document to train on

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the index after training
```

#### Example

```bash
$ classifier train:document MyIndex spam "This is spam"
```

*train:directory*

```
Usage:
 train:directory [-i|--include[="..."]] [-c|--classifier[="..."]] [-p|--prepare] index directory

Arguments:
 index                 Name of index
 directory             The directory to train on

Options:
 --include (-i)        The categories from the directory to include (multiple values allowed)
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the index after training

```

#### Examples

```bash
$ classifier train:directory MyIndex ./mydocs/
$ classifier train:directory -i MyCategory MyIndex ./mydocs/
```

*train:pdo*

```
Usage:
 train:pdo [-c|--classifier[="..."]] [-p|--prepare] index category column query dsn [username] [password]

Arguments:
 index                 Name of index
 category              Which category this data is
 column                Which column to select
 query                 The query to run
 dsn                   The dsn to use
 username              The username to use
 password              The password to use

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the index after training
```

#### Example

```bash
$ classifier train:pdo MyIndex spam Comment "SELECT Comment FROM Comment WHERE Spam = 1" "mysql:dbname=mydb;host=127.0.0.1" root root
```

*index:create*

```
Usage:
 index:create index

Arguments:
 index                 Name of index
```

#### Example

```bash
$ classifier index:create MyIndex
```

*index:remove*

```
Usage:
 index:remove index

Arguments:
 index                 Name of index
```

#### Example

```bash
$ classifier index:remove MyIndex
```

*index:prepare*

```
Usage:
 index:prepare index

Arguments:
 index                 Name of index
```

#### Example

```bash
$ classifier index:prepare MyIndex
```

*classify*

```
Usage:
 classify [-c|--classifier[="..."]] index document

Arguments:
 index                 Name of index
 document              The document to classify

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
```

#### Example

```bash
$ classifier classify MyIndex "Some document to classify"
```

*server:start*

```
Usage:
 server:start [--host[="..."]] [-p|--port[="..."]]

Options:
 --host                Set a host (default: "127.0.0.1")
 --port (-p)           Set a port (default: 1337)
```

#### Examples

```bash
$ classifier server:start
$ classifier server:start -p 9999
$ classifier server:start &
```

*generate-container*

```
Usage:
 generate-container
```

#### Examples

```bash
$ classifier generate-container
```

*config:create*

```
Usage:
 config:create [-g|--global]

Options:
 --global (-g)         Uses global config applied across all users
```

#### Examples

```bash
$ classifier config:create
$ classifier config:create -g
```

*config:remove*

```
Usage:
 config:remove [-g|--global]

Options:
 --global (-g)         Uses global config applied across all users
```

#### Examples

```bash
$ classifier config:remove
$ classifier config:remove -g
```

*config:open*

```
Usage:
 config:open [-g|--global]

Options:
 --global (-g)         Uses global config applied across all users
```

#### Examples

```bash
$ classifier config:open
$ classifier config:open -g
```

# Technical details

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

# Dependency injection (Symfony)

This library uses Symfony's dependancy injection component. A [container extension](http://symfony.com/doc/2.1/components/dependency_injection/compilation.html) is provided, and a container is also provided so if you aren't already using Symfony's dependancy injection component you can still take advantage of the default services provided.

# Unit testing

    statistical-classifier/ $ composer install --dev
    statistical-classifier/ $ vendor/bin/phpunit
