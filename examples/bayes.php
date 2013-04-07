<?php

ini_set('memory_limit', '6G');

require_once __DIR__ . '/../src/bootstrap.php';

use Camspiers\StatisticalClassifier\DataSource\Directory;
use Camspiers\StatisticalClassifier\Index\CachedIndex;

$c = new StatisticalClassifierServiceContainer;

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

$c->set(
    'index.index',
    new CachedIndex(
        '20news-bydate',
        $c->get('cache'),
        new Directory(
            array(
                'directory' => __DIR__ . '/../resources/20news-bydate/20news-bydate-train',
                'include' => $cats
            )
        )
    )
);

$nb = $c->get('classifier.complement_naive_bayes');

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
    if ($nb->classify($document['document']) == $document['category']) {
        $stats[$document['category']][0]++;
    }
    $stats[$document['category']][1]++;
}

foreach ($stats as $category => $data) {
    echo $category, ': ', ($data[0] / $data[1]), PHP_EOL;
}
