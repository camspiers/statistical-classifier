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

$data = $testSource->getData();
$stats = array();

foreach ($data as $category => $documents) {
    $stats[$category] = array(0, count($documents));
    foreach ($documents as $document) {
        if ($classifier->is($category, $document)) {
            $stats[$category][0]++;
        }
    }
}

foreach ($stats as $category => $data) {
    echo $category, ': ', ($data[0] / $data[1]), PHP_EOL;
}
