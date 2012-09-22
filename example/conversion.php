<?php

require '../vendor/autoload.php';

use Camspiers\StatisticalClassifier\DataSource\Json;
use Camspiers\StatisticalClassifier\DataSource\PDOTable;
use Camspiers\StatisticalClassifier\DataSource\Converter;

$to = new Json('vsa.json');

$from = new PDOTable(
    'mysql:dbname=dev_vsa;host=127.0.0.1;port=8889',
    'root',
    'root',
    'PageComment',
    'Comment',
    'IsSpam',
    array(
        1 => 'Spam',
        0 => 'Ham'
    )
);

$converter = new Converter($from, $to);
$converter->run();
