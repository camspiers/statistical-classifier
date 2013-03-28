<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Camspiers\StatisticalClassifier\Config\Config;
use Camspiers\StatisticalClassifier\Console\Command\GenerateContainerCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

if (!file_exists(Config::getConfigOption('container_dir') . '/StatisticalClassifierServiceContainer.php')) {
    $command = new GenerateContainerCommand();
    $command->run(
        new ArrayInput(array()),
        new NullOutput()
    );
}
