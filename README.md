# PHP Classifier

[![Build Status](https://travis-ci.org/camspiers/statistical-classifier.png?branch=master)](https://travis-ci.org/camspiers/statistical-classifier)
[![Latest Stable Version](https://poser.pugx.org/camspiers/statistical-classifier/v/stable.png)](https://packagist.org/packages/camspiers/statistical-classifier)

PHP Classifier uses [semantic versioning](http://semver.org/), it is currently at major version 0, so the public API should not be considered stable.

# What is it?

PHP Classifier is a text classification library with a focus on reuse, customizability and performance.
Classifiers can be used for many purposes, but are particularly useful in detecting spam.

## Features

* Complement Naive Bayes Classifier
* SVM (libsvm) Classifier
* Highly customizable (easily modify or build your own classifier)
* Command-line interface (phar archive)
* Multiple **data import types** to get your data into the classifier (Directory of files, Database queries, Json, Serialized arrays)
* Multiple **types of caching** for the model the classifier builds
* Easy integration in Symfony applications

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

PHP Classifier can run in `hhvm` which dramatically decreases run-time and memory usage. See [HipHop VM for PHP](https://github.com/facebook/hiphop-php/) for installation instructions.

# Usage

## Non-cached Naive Bayes

```php
<?php
//Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

$classifier = new ComplementNaiveBayes($source = new DataArray());

$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');

$classifier->is('ham', 'Some ham document'); // bool(true)
$classifier->classify('Some ham document'); // string "ham"
```

## Non-cached SVM

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier\SVM;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

$classifier = new SVM($source = new DataArray());

$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');

$classifier->is('ham', 'Some ham document'); // bool(true)
$classifier->classify('Some ham document'); // string "ham"
```

## Cached Naive Bayes

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\Model\CachedModel;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

$source = new DataArray();
$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');

$model = new CachedModel(
	'mycachename',
	new CacheCache\Cache(
		new CacheCache\Backends\File(
			array(
				'dir' => __DIR__
			)
		)
	)
);

$classifier = new ComplementNaiveBayes($source, $model);

$classifier->is('ham', 'Some ham document'); // bool(true)
$classifier->classify('Some ham document'); // string "ham"
```

## Cached SVM

```php
<?php
// Ensure composer autoloader is required
use Camspiers\StatisticalClassifier\Classifier\SVM;
use Camspiers\StatisticalClassifier\Model\SVMCachedModel;
use Camspiers\StatisticalClassifier\DataSource\DataArray;

$source = new DataArray();
$source->addDocument('spam', 'Some spam document');
$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Some ham document');
$source->addDocument('ham', 'Another ham document');

$model = new Model\SVMCachedModel(
	__DIR__ . '/model.svm',
	new CacheCache\Cache(
		new CacheCache\Backends\File(
			array(
				'dir' => __DIR__
			)
		)
	)
);

$classifier = new SVM($source, $model);

$classifier->is('ham', 'Some ham document'); // bool(true)
$classifier->classify('Some ham document'); // string "ham"
```

## With Symfony Dependency Injection

A container extension `StatisticalClassifierExtension` is available which allows for easy integration with Symfony apps.

## From command-line

### Commands

#### Overview

```
Available commands:
  classify             Classify a document
  generate-container   Generate container
  help                 Displays help for a command
  list                 Lists commands
  self-update          Update the classifier
config
  config:create        Creates the config
  config:open          Opens the config
  config:remove        Removes the config
model
  model:prepare        Prepare an model
  model:remove         Remove a model
server
  server:run           Run a classifier server
test
  test:directory       Test the classifier against a directory
  test:pdo             Test the classifier with a PDO query
train
  train:directory      Train the classifier with a directory
  train:document       Train the classifier with a document
  train:pdo            Train the classifier with a PDO query
```

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

# Unit testing

    statistical-classifier/ $ composer install --dev
    statistical-classifier/ $ phpunit
