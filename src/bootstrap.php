<?php

/**
 * Support autoloading when running as installed package
 */
use Camspiers\StatisticalClassifier\Config\Config;
use Camspiers\StatisticalClassifier\Console\Command\GenerateContainerCommand;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
);

/**
 * Loop through the potential autoload locations
 */
foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require_once $file;
        break;
    }
}

/**
 * If the autoloader isn't returning a autoload then error
 */
if (is_null($loader) || !$loader instanceof ClassLoader) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

/**
 * Based on where the autoloader was found set the run path
 */
Config::setRunPath(dirname(dirname(realpath($file))));
/**
 * Set the classifier packages path
 */
Config::setClassifierPath(dirname(__DIR__));

$containerClass = Config::getOption('container_class');
$containerDir = Config::getOptionPath('container_dir');

/**
 * If the container doesn't exist generate one before requiring it
 */
if (!file_exists("$containerDir/$containerClass.php")) {
    $command = new GenerateContainerCommand();
    $command->run(
        new ArrayInput(array()),
        new NullOutput()
    );
}

/**
 * Require the container
 */
require_once "$containerDir/$containerClass.php";
