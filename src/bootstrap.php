<?php

use Camspiers\StatisticalClassifier\Console;

require_once __DIR__ . '/../vendor/autoload.php';

if (!file_exists(__DIR__ . '/../config/StatisticalClassifierServiceContainer.php')) {

    // $app = new Console\Application;
    $command = new Console\Command\GenerateContainerCommand();

    $command->run(
        new Symfony\Component\Console\Input\ArrayInput(
            array()
        ),
        new Symfony\Component\Console\Output\NullOutput()
    );

}
