<?php

/*
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Classifier\ClassifierInterface;
use Camspiers\StatisticalClassifier\Classifier\TokenCountByDocumentInterface;
use RuntimeException;

// class TFThreaded implements TransformInterface
// {

//     //in construct set slice length
//     public function apply(ClassifierInterface $classifier)
//     {
//         if (!$classifier instanceof TokenCountByDocumentInterface) {
//             throw new RuntimeException('Classifier must implement TokenCountByDocumentInterface');
//         }
//         $transform = $tokenCountByDocument = $classifier->getTokenCountByDocument();
//         $bench = new \Ubench;
//         $bench->start();
//         foreach ($tokenCountByDocument as $category => $documents) {
//             $transform[$category] = array();
//             $chunks = array_chunk($documents, count($documents) / 1);
//             $threads = array();
//             foreach ($chunks as $documents) {
//                 $stackable = new TFStackable();
//                 $stackable['data'] = $documents;
//                 $threads[] = new TFThread($stackable);
//             }
//             foreach ($threads as $thread) {
//                 $transform[$category] = $transform[$category] + $thread->getData();
//             }
//         }
//         $bench->end();
//         echo $bench->getTime();
//         $classifier->setTokenCountByDocument($transform);
//     }
// }

// class TFThread extends \Thread
// {
//     public function __construct(\Stackable $data)
//     {
//         $this->data = $data;
//         $this->start();
//     }
//     public function run()
//     {
//         echo 'Thread start', PHP_EOL;
//         $data = $this->data['data'];
//         foreach ($data as $index => $document) {
//             foreach ($document as $token => $count) {
//                 $data[$index][$token] = log($count + 1, 10);
//             }
//         }
//         echo 'Data finished', PHP_EOL;
//         $this->data['data'] = $data;
//     }
//     public function getData()
//     {
//         if (!$this->joined) {
//             $this->joined = true;
//             $this->join();
//         }
//         echo 'Thread end', PHP_EOL;
//         return $this->data['data'];
//     }
// }

// class TFStackable extends \Stackable
// {
//     public $counter = 0;
//     public function run(){}
// }
