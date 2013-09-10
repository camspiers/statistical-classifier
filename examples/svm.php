<?php

ini_set('memory_limit', '2G');

require_once __DIR__ . '/../src/bootstrap.php';

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Camspiers\StatisticalClassifier\Model\SVMCachedModel;
use Camspiers\StatisticalClassifier\Classifier\SVM;

$c = new StatisticalClassifierServiceContainer;

$cats = array(
    'alt.atheism',
    'comp.graphics',
    'rec.motorcycles',
    'sci.crypt',
    'soc.religion.christian',
    'talk.religion.misc'
);

$source = new Directory(
    array(
        'directory' => __DIR__ . '/../resources/20news-bydate/20news-bydate-train',
        'include' => $cats
    )
);

$model = new SVMCachedModel(
    __DIR__ . '/model.svm',
    $c->get('cache')
);

$classifier = new SVM(
    $source,
    $model,
    null,
    $c->get('normalizer.stopword_lowercase')
);

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
