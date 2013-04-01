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

use Camspiers\StatisticalClassifier\Config\Config;
use Camspiers\StatisticalClassifier\Console\Command\Command;
use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
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
            ->setDescription('Generate container')
            ->addOption(
                'services',
                's',
                Input\InputOption::VALUE_OPTIONAL,
                'Use the specified services file to generate the container'
            );
    }
    /**
     * Generate the container
     * @param  Input\InputInterface   $input  The commands input
     * @param  Output\OutputInterface $output The commands output
     * @throws RuntimeException
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $config = Config::getConfig();

        $servicesFile = $input->getOption('services');
        $containerDir = Config::getOptionPath('container_dir');

        if (!$servicesFile) {
            $servicesFile = Config::getOptionPath('services_path');
        }

        if (!file_exists($servicesFile)) {
            throw new RuntimeException("Services file '$servicesFile' doesn't exist");
        }

        if (!file_exists($containerDir)) {
            throw new RuntimeException("Dump location '$containerDir' does not exist");
        }

        $this->includeFiles($config);

        $container = new ContainerBuilder();

        $this->addExtensions($container, $config);

        $this->addCompilerPasses($container, $config);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname($servicesFile))
        );

        $loader->load(basename($servicesFile));

        if (isset($config['parameters'])) {
            $container->getParameterBag()->add($config['parameters']);
        }

        $container->compile();

        $dumper = new PhpDumper($container);

        file_put_contents(
            $containerDir . "/{$config['container_class']}.php",
            $dumper->dump(
                array(
                    'class' => $config['container_class']
                )
            )
        );

        $output->writeLn('Container generated');
    }

    protected function includeFiles($config)
    {
        if (isset($config['require']) && is_array($config['require'])) {
            foreach ($config['require'] as $file) {
                if (file_exists($file)) {
                    require_once $file;
                } else {
                    require_once Config::getClassifierPath() . '/' . $file;
                }
            }
        }
    }

    protected function addExtensions(ContainerBuilder $container, $config)
    {
        if (isset($config['extensions']) && is_array($config['extensions'])) {
            foreach ($config['extensions'] as $extension) {
                $container->registerExtension(
                    new $extension
                );
            }
        }
    }
    protected function addCompilerPasses(ContainerBuilder $container, $config)
    {
        if (isset($config['compiler_passes']) && is_array($config['compiler_passes'])) {
            foreach ($config['compiler_passes'] as $compilerPass) {
                $container->addCompilerPass(
                    new $compilerPass
                );
            }
        }
    }
}
