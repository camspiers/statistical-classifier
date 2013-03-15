<?php
require __DIR__ . '/../vendor/autoload.php';
// Ensure composer autoloader is required
$c = new StatisticalClassifierServiceContainer;
// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Index\Index;
// This sets the data source to the soon created classifier using a synthetic symfony service
$c->set(
    'index.index',
    new Index(
        new DataArray(
            array(
                'spam' => array(
                    'Some spam document',
                    'Another spam document'
                ),
                'ham' => array(
                    'Some ham document',
                    'Another ham document'
                )
            )
        )
    )
);
echo $c->get('classifier.naive_bayes')->classify("Some ham document"), PHP_EOL;
