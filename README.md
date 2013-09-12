# PHP Classifier

[![Build Status](https://travis-ci.org/camspiers/statistical-classifier.png?branch=master)](https://travis-ci.org/camspiers/statistical-classifier)

NOTE: PHP Classifier uses [semantic versioning](http://semver.org/), it is currently at major version 0, so the public API should not be considered stable.

# What is it?

> In machine learning and statistics, classification is the problem of identifying to which of a set of categories (sub-populations) a new observation belongs, on the basis of a training set of data containing observations (or instances) whose category membership is known. - [Wikipedia - Statistical Classification](http://en.wikipedia.org/wiki/Statistical_classification)

PHP Classifier is written entirely in PHP, with a focus on reuse and customizability, allowed by dependancy injection and interfaces.

Important Features:

* Complement Naive Bayes Classifier & SVM (libsvm) Classifier
* Highly customizable
* Multiple ways of using the library (PHP, command-line, HTTP server)
* Support for use from non-PHP programming languages (through command-line or HTTP server)
* Multiple **data import types** to get your data into the classifier (Directory of files, Database queries, Json, Serialized arrays)
* Multiple **types of caching** for the model the classifier builds (Memcache, Apc, File, Session)
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
$ composer require camspiers/statistical-classifier:~0.6
```

## For command-line use

Download the [`classifier.phar`](http://php-classifier.com/classifier.phar) executable.

To stay up to date run

    $ php classifier.phar self-update

## SVM Support

For SVM Support both libsvm and php-svm are required. For installation intructions refer to [php-svm](https://github.com/ianbarber/php-svm).

## HipHop VM support

PHP Classifier runs in `hhvm` which dramatically decreases run-time and memory usage. See [HipHop VM for PHP](https://github.com/facebook/hiphop-php/) for installation instructions.

# Usage

## Without Symfony Dependency Injection

### Non-cached Naive Bayes

```php
<?php
//Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier;
use Camspiers\StatisticalClassifier\DataSource;

$c = new Classifier\ComplementNaiveBayes(
    $source = new DataSource\DataArray()
);

$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->is('ham', 'Some ham document'), PHP_EOL; // 1 (true)
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

### Non-cached SVM

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier;
use Camspiers\StatisticalClassifier\DataSource;

$c = new Classifier\SVM(
    $source = new DataSource\DataArray()
);

$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->is('ham', 'Some ham document'), PHP_EOL; // 1 (true)
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

### Cached Naive Bayes

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier;
use Camspiers\StatisticalClassifier\Model;
use Camspiers\StatisticalClassifier\DataSource;

$c = new Classifier\ComplementNaiveBayes(
    $source = new DataSource\DataArray()
    new Model\CachedModel(
        'mycachename',
        new CacheCache\Cache(
            new CacheCache\Backends\File(
                array(
                    'dir' => __DIR__
                )
            )
        )
    )
);
$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->is('ham', 'Some ham document'), PHP_EOL; // 1 (true)
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

### Cached SVM

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier;
use Camspiers\StatisticalClassifier\Model;
use Camspiers\StatisticalClassifier\DataSource;

$c = new Classifier\SVM(
    $source = new DataSource\DataArray(),
    new Model\SVMCachedModel(
        __DIR__ . '/model.svm',
        new CacheCache\Cache(
            new CacheCache\Backends\File(
                array(
                    'dir' => __DIR__
                )
            )
        )
    )
);
$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->is('ham', 'Some ham document'), PHP_EOL; // 1 (true)
echo $c->classify('Some ham document'), PHP_EOL; // ham
```

## With Symfony Dependency Injection

### Naive Bayes

```php
<?php
// ensure bootstrap is loaded
$c = new StatisticalClassifierServiceContainer;
// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Model\Model;
// This sets the model to the soon created classifier using a synthetic symfony service
$c->set(
    'classifier.source',
    $source = new DataArray()
);
$c->set(
    'classifier.model',
    new Model()
);
$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->get('classifier.complement_naive_bayes')->classify("Some ham document"), PHP_EOL; //ham
```

### SVM Cached

```php
<?php
// ensure bootstrap is loaded
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Model\SVMCachedModel;

$c->set(
    'classifier.source',
    $source = new DataArray()
);

$c->set(
    'classifier.model',
    new SVMCachedModel(
        __DIR__ . '/model.svm',
        $c->get('cache')
    )
);

$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');
echo $c->get('classifier.svm')->classify("Some ham document"), PHP_EOL; //ham
```

## From command-line

### Commands

*train:document*

```
Usage:
 train:document [-c|--classifier[="..."]] [-p|--prepare] model category document

Arguments:
 model                 Name of model
 category              Which category this data is
 document              The document to train on

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the model after training
```

#### Example

```bash
$ classifier train:document MyModel spam "This is spam"
```

*train:directory*

```
Usage:
 train:directory [-i|--include[="..."]] [-c|--classifier[="..."]] [-p|--prepare] model directory

Arguments:
 model                 Name of model
 directory             The directory to train on

Options:
 --include (-i)        The categories from the directory to include (multiple values allowed)
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the model after training

```

#### Examples

```bash
$ classifier train:directory MyModel ./mydocs/
$ classifier train:directory -i MyCategory MyModel ./mydocs/
```

*train:pdo*

```
Usage:
 train:pdo [-c|--classifier[="..."]] [-p|--prepare] model category column query dsn [username] [password]

Arguments:
 model                 Name of model
 category              Which category this data is
 column                Which column to select
 query                 The query to run
 dsn                   The dsn to use
 username              The username to use
 password              The password to use

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
 --prepare (-p)        Prepare the model after training
```

#### Example

```bash
$ classifier train:pdo MyModel spam Comment "SELECT Comment FROM Comment WHERE Spam = 1" "mysql:dbname=mydb;host=127.0.0.1" root root
```

*model:remove*

```
Usage:
 model:remove model

Arguments:
 model                 Name of model
```

#### Example

```bash
$ classifier model:remove MyModel
```

*model:prepare*

```
Usage:
 model:prepare model

Arguments:
 model                 Name of model
```

#### Example

```bash
$ classifier model:prepare MyModel
```

*classify*

```
Usage:
 classify [-c|--classifier[="..."]] model document

Arguments:
 model                 Name of model
 document              The document to classify

Options:
 --classifier (-c)     Name of classifier (default: "classifier.complement_naive_bayes")
```

#### Example

```bash
$ classifier classify MyModel "Some document to classify"
```

*server:run*

```
Usage:
 server:run [--host[="..."]] [-p|--port[="..."]]

Options:
 --host                Set a host (default: "127.0.0.1")
 --port (-p)           Set a port (default: 1337)
```

#### Examples

```bash
$ classifier server:run
$ classifier server:run -p 9999
$ classifier server:run &
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

# Dependency injection (Symfony)

This library uses Symfony's dependancy injection component. A [container extension](http://symfony.com/doc/2.1/components/dependency_injection/compilation.html) is provided, and a container is also provided so if you aren't already using Symfony's dependancy injection component you can still take advantage of the default services provided.

# Unit testing

    statistical-classifier/ $ composer install --dev
    statistical-classifier/ $ vendor/bin/phpunit
