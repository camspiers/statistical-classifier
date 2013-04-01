<?php

use Camspiers\StatisticalClassifier\Config\Config;
use Camspiers\StatisticalClassifier\Console\Command\GenerateContainerCommand;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Support autoloading when running as installed package
 */
$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require_once $file;
        break;
    }
}

Config::setRunPath(dirname(dirname(realpath($file))));
Config::setClassifierPath(dirname(__DIR__));

if (is_null($loader) || !$loader instanceof ClassLoader) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

$containerClass = Config::getOption('container_class');
$containerDir = Config::getOptionPath('container_dir');

if (!file_exists("$containerDir/$containerClass.php")) {
    $command = new GenerateContainerCommand();
    $command->run(
        new ArrayInput(array()),
        new NullOutput()
    );
}

require_once "$containerDir/$containerClass.php";
