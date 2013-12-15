---
layout: default
title: PHP Classifier
---
PHP Classifier uses [semantic versioning](http://semver.org/), it is currently at major version 0, so the public API should not be considered stable.

# What is it?

PHP Classifier is a text classification library with a focus on reuse, customizability and performance.
Classifiers can be used for many purposes, but are particularly useful in detecting spam.

## Features

* Complement Naive Bayes Classifier
* SVM (libsvm) Classifier
* Highly customizable (easily modify or build your own classifier)
* Command-line interface via separate library (phar archive)
* Multiple **data import types** to get your data into the classifier (Directory of files, Database queries, Json, Serialized arrays)
* Multiple **types of caching** for the model the classifier builds
* Easy integration in Symfony applications

# Installation

```bash
$ composer require camspiers/statistical-classifier
```

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

# Caching models

Caching models requires [maximebf/CacheCache](https://github.com/maximebf/CacheCache) which can be installed via packagist. Additional caching systems can be easily integrated.

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

# Unit testing

    statistical-classifier/ $ composer install --dev
    statistical-classifier/ $ phpunit