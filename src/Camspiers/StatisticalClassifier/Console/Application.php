<?php

namespace Camspiers\StatisticalClassifier\Console;

use Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{

    public function __construct()
    {
        parent::__construct('Statistical Classifier', '0.1');
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new Command\TrainCommand();
        $commands[] = new Command\ClassifyCommand();

        return $commands;
    }
}
