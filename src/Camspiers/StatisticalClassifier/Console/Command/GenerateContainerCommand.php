<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\Console\Command\Command as BaseCommand;

use Camspiers\DependencyInjection\SharedContainerFactory;
use Camspiers\StatisticalClassifier\DependencyInjection;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class GenerateContainerCommand extends BaseCommand
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('generate-container')
            ->setDescription('Generate container');
    }
    /**
     * Generate the container
     * @param  Input\InputInterface   $input  The commands input
     * @param  Output\OutputInterface $output The commands output
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        SharedContainerFactory::addExtension(
            new DependencyInjection\StatisticalClassifierExtension()
        );

        SharedContainerFactory::addCompilerPass(
            new DependencyInjection\CommandCompilerPass()
        );

        $container = SharedContainerFactory::createContainer(
            array()
        );

        $container->loadFromExtension('statistical_classifier');

        SharedContainerFactory::dumpContainer(
            $container,
            'StatisticalClassifierServiceContainer',
            'config/'
        );

        SharedContainerFactory::clearExtensions();
        SharedContainerFactory::clearCompilerPasses();
    }
}
