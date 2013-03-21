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

use Camspiers\DependencyInjection\SharedContainerFactory;
use Camspiers\StatisticalClassifier\DependencyInjection;
use Camspiers\StatisticalClassifier\Console\Command\Command;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class GenerateContainerCommand extends Command
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

        $config = $this->getConfig();

        if (isset($config['require']) && is_array($config['require'])) {
            foreach ($config['require'] as $file) {
                if (file_exists($file)) {
                    include_once $file;
                } else {
                    include_once $config['basepath'] . $file;
                }
            }
        }

        if (isset($config['extensions']) && is_array($config['extensions'])) {
            foreach ($config['extensions'] as $extension) {
                SharedContainerFactory::addExtension(
                    new $extension
                );
            }
        }
        if (isset($config['compiler_passes']) && is_array($config['compiler_passes'])) {
            foreach ($config['compiler_passes'] as $compilerPass) {
                SharedContainerFactory::addCompilerPass(
                    new $compilerPass
                );
            }
        }

        SharedContainerFactory::dumpContainer(
            SharedContainerFactory::createContainer(
                array(),
                $config['basepath'] . $config['services']
            ),
            'StatisticalClassifierServiceContainer',
            'config/'
        );

        SharedContainerFactory::clearExtensions();
        SharedContainerFactory::clearCompilerPasses();

        $output->writeLn('Container generated');
    }
}
