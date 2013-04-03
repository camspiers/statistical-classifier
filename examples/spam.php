<?php

/**
 * Warning: This file downloads another file which is ~15MB
 */

ini_set('memory_limit', '1G');

require_once __DIR__ . '/../src/bootstrap.php';

use Camspiers\StatisticalClassifier\Index\CachedIndex;
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\Closure;

$c = new StatisticalClassifierServiceContainer;

$nb = new ComplementNaiveBayes(
    new CachedIndex(
        'spam',
        $c->get('cache'),
        new Closure(
            function () {
                return json_decode(file_get_contents('http://php-classifier.com/resources/spam.json'));
            }
        )
    )
);

echo $nb->classify("Reese Witherspoon Going Harder, More Urgent http://townsendantiques.com/worstmanifest/8FwS3znrMkjlINquS203cksM7/"), PHP_EOL;
