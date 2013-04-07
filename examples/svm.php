<?php

ini_set('memory_limit', '2G');

require_once __DIR__ . '/../src/bootstrap.php';

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Camspiers\StatisticalClassifier\Index\SVMCachedIndex;

$c = new StatisticalClassifierServiceContainer;

$cats = array(
    'alt.atheism',
    'comp.graphics',
    'rec.motorcycles',
    'sci.crypt',
    'soc.religion.christian',
    'talk.religion.misc'
);

$c->set(
    'index.index',
    new SVMCachedIndex(
        __DIR__ . '/model.svm',
        'svm',
        $c->get('cache'),
        new Directory(
            array(
                'directory' => __DIR__ . '/../resources/20news-bydate/20news-bydate-train',
                'include' => $cats
            )
        )
    )
);

$classifier = $c->get('classifier.svm');

$testSource = new Directory(
    array(
        'directory' => __DIR__ . '/../resources/20news-bydate/20news-bydate-test',
        'include' => $cats
    )
);

$documents = $testSource->getData();
$stats = array();

foreach ($documents as $document) {
    if (!isset($stats[$document['category']])) {
        $stats[$document['category']] = array(0, 0);
    }
    if ($classifier->classify($document['document']) == $document['category']) {
        $stats[$document['category']][0]++;
    }
    $stats[$document['category']][1]++;
}

foreach ($stats as $category => $data) {
    echo $category, ': ', ($data[0] / $data[1]), PHP_EOL;
}
