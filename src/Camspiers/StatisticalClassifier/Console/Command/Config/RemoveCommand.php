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

use Camspiers\StatisticalClassifier\Console\Command\Config\Command;
use RuntimeException;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class RemoveCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('config:remove')
            ->setDescription('Removes the config')
            ->configureGlobal();
    }
    /**
     * Create the model using the specified name
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @throws \RuntimeException
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $filename = $this->getConfigFilename($input);
        if (file_exists($filename)) {
            unlink($filename);
            $output->writeLn("Config file '$filename' removed");
        } else {
            throw new RuntimeException("Config file '$filename' doesn't exist");
        }
    }
}
