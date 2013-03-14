<?php
require __DIR__ . '/../vendor/autoload.php';
// Ensure composer autoloader is required
$container = new StatisticalClassifierServiceContainer;
// Using a plain data array source for simplicity
use Camspiers\StatisticalClassifier\DataSource\DataArray;
// This sets the data source to the soon created classifier using a synthetic symfony service
$container->set(
    'data_source.data_source',
    new DataArray(array(
        'spam' => array(
            'Some spam document',
            'Another spam document'
        ),
        'ham' => array(
            'Some ham document',
            'Another ham document'
        )
    ))
);

$nb = $container->get('classifier.naive_bayes');

echo $nb->classify("Some ham document"), PHP_EOL;
