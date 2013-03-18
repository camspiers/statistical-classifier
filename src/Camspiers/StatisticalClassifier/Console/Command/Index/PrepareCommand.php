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

use Camspiers\StatisticalClassifier\Console\Command\CacheableCommand;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PrepareCommand extends CacheableCommand
{
    protected function configure()
    {
        $this
            ->setName('index:prepare')
            ->setDescription('Prepare an index')
            ->addArgument(
                'index',
                Input\InputArgument::REQUIRED,
                'Name of index'
            );
    }

    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $c = $this->getApplication()->getContainer();
        $c->set(
            'index.index',
            $this->getCachedIndex(
                $input->getArgument('index')
            )
        );
        $c->get('classifier.naive_bayes')->prepareIndex();
    }
}
