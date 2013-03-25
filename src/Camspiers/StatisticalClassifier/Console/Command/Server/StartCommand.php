<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Console\Command\Server;

use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

use Camspiers\StatisticalClassifier\Console\Command\Command;
use Camspiers\StatisticalClassifier\Index;
use Camspiers\StatisticalClassifier\Classifier\ClassifierInterface;

use React\EventLoop;
use React\Socket;
use React\Http;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class StartCommand extends Command
{
    /**
     * Holds classifier instances
     * @var array
     */
    protected $classifiers = array();
    /**
     * Holds index instances
     * @var array
     */
    protected $indexes = array();
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('server:start')
            ->setDescription('Start a classifier server')
            ->addOption(
                'host',
                null,
                Input\InputOption::VALUE_OPTIONAL,
                'Set a host',
                '127.0.0.1'
            )
            ->addOption(
                'port',
                'p',
                Input\InputOption::VALUE_OPTIONAL,
                'Set a port',
                1337
            );
    }
    /**
     * Start a classifier server
     * @param  Input\InputInterface   $input  The input object
     * @param  Output\OutputInterface $output The output object
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        // Set a dummy index
        $this->getContainer()->set(
            'index.index',
            new Index\Index()
        );

        $http = new Http\Server(
            $socket = new Socket\Server(
                $loop = EventLoop\Factory::create()
            )
        );

        $http->on(
            'request',
            array($this, 'serve')
        );

        $socket->listen(
            $input->getOption('port'),
            $input->getOption('host')
        );

        $loop->run();

    }
    /**
     * Serve a request
     * @param  Http\Request  $request  The request object
     * @param  Http\Response $response The response object
     * @return null
     */
    public function serve(
        Http\Request $request,
        Http\Response $response
    ) {
        switch ($request->getPath()) {
            case '/classify':
            case '/classify/':
                $this->classify(
                    $request,
                    $response
                );
                break;
            default:
                $response->writeHead(404, array('Content-Type' => 'text/plain'));
                $response->end('Not found');
                break;

        }
    }
    /**
     * Respond to a classify request
     * @param  Http\Request  $request  The request object
     * @param  Http\Response $response The response object
     * @return null
     */
    protected function classify(
        Http\Request $request,
        Http\Response $response
    ) {

        $query = $request->getQuery();

        if (isset($query['index'])) {

            $classifier = $this->getClassifierByType(isset($query['classifier']) ? $query['classifier'] : 'classifier.naive_bayes');

            $classifier->setIndex(
                $this->getIndex(
                    $query['index'],
                    isset($query['fresh'])
                )
            );

            $response->writeHead(
                200,
                array(
                    'Content-Type' => 'application/json'
                )
            );

            if (isset($query['document'])) {

                $response->end(
                    json_encode(
                        array(
                            'category' => $classifier->classify($query['document'])
                        )
                    )
                );

            } else {

                $request->on(
                    'data',
                    function ($document) use ($response, $classifier) {
                        $response->end(
                            json_encode(
                                array(
                                    'category' => $classifier->classify($document)
                                )
                            )
                        );
                    }
                );

            }

        } else {

            $response->writeHead(400, array('Content-Type' => 'text/plain'));
            $response->end('Bad request an index must be specified');

        }

    }
    /**
     * Get a classifier from the container using a service name
     * @param  string $classifierType The service name
     * @return ClassifierInterface    The classifier
     */
    protected function getClassifierByType($classifierType)
    {
        if (!isset($this->classifiers[$classifierType])) {
            $this->classifiers[$classifierType] = $this->getContainer()->get($classifierType);
        }
        return $this->classifiers[$classifierType];
    }
    /**
     * Get a cached index by name
     * @param  string $name  The index name
     * @param  boolean $fresh Whether or not to get a fresh one even if it exists
     * @return Index\CachedIndex The index
     */
    protected function getIndex($name, $fresh = false)
    {   
        if (!isset($this->indexes[$name]) || $fresh) {
            $this->indexes[$name] = $this->getCachedIndex($name);
        }
        return $this->indexes[$name];
    }
}
