<?php

ini_set('memory_limit', '512M');

require_once __DIR__ . '/../vendor/autoload.php';

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;

$cats = array(
    'alt.atheism',
    'comp.graphics',
    'rec.motorcycles',
    'sci.crypt',
    'soc.religion.christian',
    'talk.religion.misc'
);

//bayes
//alt.atheism: 0.81504702194357
//comp.graphics: 0.88688946015424
//rec.motorcycles: 0.98743718592965
//sci.crypt: 0.93939393939394
//soc.religion.christian: 0.90954773869347
//talk.religion.misc: 0.70517928286853

$classifier = new ComplementNaiveBayes(
    new Directory(
        array(
            'directory' => __DIR__ . '/../resources/20news-bydate/20news-bydate-train',
            'include' => $cats
        )
    )
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
