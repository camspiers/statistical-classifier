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

use Camspiers\StatisticalClassifier\Classifier\Classifier;
use Camspiers\StatisticalClassifier\Console\Command\Command;
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Model;
use React\EventLoop;
use React\Http;
use React\Socket;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class RunCommand extends Command
{
    /**
     * Holds classifier instances
     * @var array
     */
    protected $classifiers = array();
    /**
     * Holds model instances
     * @var array
     */
    protected $models = array();
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('server:run')
            ->setDescription('Run a classifier server')
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
        // Set a dummy model
        $this->getContainer()->set(
            'classifier.model',
            new Model\Model()
        );
        $this->getContainer()->set(
            'classifier.source',
            new DataArray()
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

        $output->writeln('Server started at ' . $input->getOption('host') . ':' . $input->getOption('port'));

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
        $path = rtrim($request->getPath(), '/');
        switch ($path) {
            case '/classify':
                $this->classify($request, $response);
                break;
            case '/train':
                $this->train($request, $response);
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

        if (isset($query['model'])) {

            $classifierType = isset($query['classifier']) ? $query['classifier'] : 'classifier.complement_naive_bayes';

            $classifier = $this->getClassifierByType($classifierType);

            $classifier->setModel($this->getModel($query['model'], $classifierType == 'classifier.svm'));
            
            $classifier->setDataSource($this->getDataSource($query['model']));

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
            $response->end('Bad request, a model must be specified');

        }

    }
    /**
     * Train an model with a document
     * @param Http\Request  $request
     * @param Http\Response $response
     */
    protected function train(
        Http\Request $request,
        Http\Response $response
    ) {

        $query = $request->getQuery();

        if (isset($query['model']) && isset($query['category'])) {

            $response->writeHead(
                200,
                array(
                    'Content-Type' => 'application/json'
                )
            );

            if (isset($query['document'])) {

                $datasource = $this->getDataSource($query['model']);

                $datasource->addDocument($query['category'], $query['document']);

                $this->cacheDataSource($query['model']);

                $response->end(
                    json_encode(
                        array(
                            'success' => true
                        )
                    )
                );

            } else {

                $request->on(
                    'data',
                    function ($document) use ($response, $query) {

                        $datasource = $this->getDataSource($query['model']);

                        $datasource->addDocument($query['category'], $document);

                        $this->cacheDataSource($query['model']);

                        $response->end(
                            json_encode(
                                array(
                                    'success' => true
                                )
                            )
                        );
                    }
                );

            }
            
            $this->getModel($query['model'])->setPrepared(false);

        } else {

            $response->writeHead(400, array('Content-Type' => 'text/plain'));
            $response->end('Bad request an model must be specified');

        }

    }
    /**
     * Get a classifier from the container using a service name
     * @param  string              $classifierType The service name
     * @return Classifier The classifier
     */
    protected function getClassifierByType($classifierType)
    {
        if (!isset($this->classifiers[$classifierType])) {
            $this->classifiers[$classifierType] = $this->getContainer()->get($classifierType);
        }

        return $this->classifiers[$classifierType];
    }
}
