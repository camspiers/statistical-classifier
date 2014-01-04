<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Classifier\SVM;

$source = new DataArray();

$source->addDocument('pig', 'Pigs are great. Pink and cute!');
$source->addDocument('wolf', 'Wolves have teeth. They are gray.');

$c = new SVM($source);
$c->setThreshold(0.6);

var_dump($c->classify('0943jf904jf09j34fpj'));
