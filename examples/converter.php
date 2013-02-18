<?php

require __DIR__ . '/../vendor/autoload.php';

$container = new StatisticalClassifierServiceContainer;

use Camspiers\StatisticalClassifier\DataSource\Serialized;
use Camspiers\StatisticalClassifier\DataSource\Directory;

// $container->set(
//     'converter.from',
//     new Directory(__DIR__ . '/../resources/20news-bydate/20news-bydate-train')
// );
// $container->set(
//     'converter.to',
//     new Serialized(__DIR__ . '/../resources/20news-bydate-train.cache')
// );
// $container->set(
//     'converter.to',
//     new Serialized(__DIR__ . '/test.cache')
// );

$container->get('converter.converter')->run();
