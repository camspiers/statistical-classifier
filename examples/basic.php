<?php

require_once __DIR__ . '/../src/bootstrap.php';

// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;

$source = new DataArray(
    array(
        array(
            'category' => 'spam',
            'document' => 'Some spam document'
        ),
        array(
            'category' => 'spam',
            'document' => 'Another spam document'
        ),
        array(
            'category' => 'ham',
            'document' => 'Some ham document'
        ),
        array(
            'category' => 'ham',
            'document' => 'Another ham document'
        )
    )
);

$source->addDocument('spam', 'Another spam document');
$source->addDocument('ham', 'Another ham document');

$c = new ComplementNaiveBayes($source);

echo $c->classify("Some ham document"), PHP_EOL;
