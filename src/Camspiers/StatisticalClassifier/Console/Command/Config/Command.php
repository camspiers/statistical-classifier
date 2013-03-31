<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command\Config;

use Camspiers\StatisticalClassifier\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
abstract class Command extends BaseCommand
{
    protected function configureGlobal()
    {
        $this->addOption(
            'global',
            'g',
            Input\InputOption::VALUE_NONE,
            'Uses global config applied across all users'
        );

        return $this;
    }

    protected function getConfigFilename(Input\InputInterface $input)
    {
        return ($input->getOption('global') ? '/usr/local' : $_SERVER['HOME']) . '/.classifier/config.json';
    }
}
