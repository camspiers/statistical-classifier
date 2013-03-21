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

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;
use Camspiers\JsonPretty\JsonPretty;;

/**
 * @author Cam Spiers <camspiers@gmail.com>
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
            ->setName('config:create')
            ->setDescription('Creates a global config');
    }
    /**
     * Create the index using the specified name
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $fileName = $_SERVER['HOME'] . '/.classifier/config.json';
        if (!file_exists($fileName)) {
            if (!file_exists($_SERVER['HOME'] . '/.classifier')) {
                mkdir($_SERVER['HOME'] . '/.classifier');
            }
            $jsonPretty = new JsonPretty();
            file_put_contents(
                $fileName,
                $jsonPretty->prettify(
                    array(
                        'require' => array(),
                        'extensions' => array(),
                        'compiler_passes' => array()
                    )
                )
            );
        }
    }
}
