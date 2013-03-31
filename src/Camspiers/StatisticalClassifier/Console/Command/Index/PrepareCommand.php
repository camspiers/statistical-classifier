<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command\Index;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;

use RuntimeException;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PrepareCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('index:prepare')
            ->setDescription('Prepare an index')
            ->configureIndex()
            ->configureClassifier();
    }
    /**
     * Prepare a specified index
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @throws \RuntimeException
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $index = $input->getArgument('index');
        if ($this->cache->exists($index)) {
            $this->getClassifier($input)->prepareIndex();
            $output->writeLn("Index '$index' was prepared");
        } else {
            throw new RuntimeException("Index '$index' doesn't exist");
        }
    }
}
