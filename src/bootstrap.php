<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Camspiers\StatisticalClassifier\Config\Config;

if (!file_exists(Config::getConfigParameter('container_dir') . '/StatisticalClassifierServiceContainer.php')) {
    $command = new Camspiers\StatisticalClassifier\Console\Command\GenerateContainerCommand();
    $command->run(
        new Symfony\Component\Console\Input\ArrayInput(array()),
        new Symfony\Component\Console\Output\NullOutput()
    );
}
