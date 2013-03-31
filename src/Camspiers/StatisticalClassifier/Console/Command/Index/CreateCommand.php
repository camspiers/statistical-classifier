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

use Camspiers\StatisticalClassifier\Console\Command\Command;
use RuntimeException;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class CreateCommand extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('index:create')
            ->setDescription('Create an index')
            ->configureIndex();
    }
    /**
     * Create the index using the specified name
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @throws \RuntimeException
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $index = $input->getArgument('index');
        if (!$this->cache->exists($index)) {
            $this->getCachedIndex($index)->preserve();
            $output->writeLn("Index '$index' was created");
        } else {
            throw new RuntimeException("Index '$index' already exists");
        }
    }
}
