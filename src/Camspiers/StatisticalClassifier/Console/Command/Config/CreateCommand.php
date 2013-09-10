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

use Camspiers\JsonPretty\JsonPretty;
use Camspiers\StatisticalClassifier\Console\Command\Config\Command;
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
     * The json pretty service
     * @var JsonPretty
     */
    protected $jsonPretty;
    /**
     * Creates the command injecting the json pretty service
     * @param JsonPretty $jsonPretty The json pretty service
     */
    public function __construct(JsonPretty $jsonPretty)
    {
        $this->jsonPretty = $jsonPretty;
        parent::__construct();
    }
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('config:create')
            ->setDescription('Creates the config')
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
        if ($input->getOption('global')) {
            if (!file_exists('/usr/local/.classifier')) {
                mkdir('/usr/local/.classifier');
            }
        } else {
            if (!file_exists($_SERVER['HOME'] . '/.classifier')) {
                mkdir($_SERVER['HOME'] . '/.classifier');
            }
        }

        if (file_exists($filename)) {
            throw new RuntimeException('Config file already exists, please run config:open to edit');
        }

        file_put_contents(
            $filename,
            $this->jsonPretty->prettify(
                array(
                    'require' => array(),
                    'extensions' => array(),
                    'compiler_passes' => array()
                )
            )
        );

        $output->writeLn("A config file was create at '$filename'");
    }
}
