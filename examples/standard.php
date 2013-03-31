<?php

ini_set('memory_limit', '6G');

require __DIR__ . '/../vendor/autoload.php';

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Camspiers\StatisticalClassifier\Index\CachedIndex;

$c = new StatisticalClassifierServiceContainer;

$cats = array(
    'alt.atheism',
    'comp.graphics',
    'rec.motorcycles',
    'sci.crypt',
    'soc.religion.christian'
);

$c->set(
    'index.index',
    new CachedIndex(
        '20news-bydate',
        $c->get('cache'),
        new Directory(
            __DIR__ . '/../resources/20news-bydate/20news-bydate-train',
            $cats
        )
    )
);

$nb = $c->get('classifier.complement_naive_bayes');

$testSource = new Directory(
    __DIR__ . '/../resources/20news-bydate/20news-bydate-test',
    $cats
);

$data = $testSource->getData();

$stats = array();
$fails = array();

foreach ($data as $category => $documents) {
    $stats[$category] = 0;
    foreach ($documents as $document) {
        if (($classifiedAs = $nb->classify($document)) == $category) {
            $stats[$category]++;
        } else {
            $fails[] = array($category, $classifiedAs, $document);
        }
    }
    echo $category, ': ', ($stats[$category] / count($documents)), PHP_EOL;
}
