<?php

ini_set('memory_limit', '6G');

require __DIR__ . '/../vendor/autoload.php';

$container = new StatisticalClassifierServiceContainer;

use Camspiers\StatisticalClassifier\DataSource\Directory;

$cats = array('alt.atheism', 'comp.graphics', 'rec.motorcycles', 'sci.crypt');

$container->set(
    'data_source.data_source',
    new Directory(__DIR__ . '/../resources/20news-bydate/20news-bydate-train', $cats)
);

$nb = $container->get('classifier.naive_bayes');

$testSource = new Directory(__DIR__ . '/../resources/20news-bydate/20news-bydate-test', $cats);

$data = $testSource->getData();

$stats = array();

foreach ($data as $category => $documents) {
    $stats[$category] = 0;
    foreach ($documents as $document) {
        if ($nb->classify($document) == $category) {
            $stats[$category]++;
        }
    }
    echo $category, ': ', ($stats[$category] / count($documents)), PHP_EOL;
}
