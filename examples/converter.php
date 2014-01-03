<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Camspiers\StatisticalClassifier\DataSource;

(new DataSource\Converter(
    new DataSource\Directory(__DIR__ . '/../resources/20news-bydate/20news-bydate-train'),
    new DataSource\Json(__DIR__ . '/../resources/converted.json')
))->run();
