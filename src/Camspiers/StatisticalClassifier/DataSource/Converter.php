<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\DataSource;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Converter
{
    private $from;
    private $to;

    public function __construct(DataSourceInterface $from, DataSourceInterface $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function run()
    {
        $data = $this->from->read();

        foreach ($data as $category => $documents) {
            foreach ($documents as $document) {
                $this->to->addDocument($category, $document);
            }
        }

        $this->to->write();
    }
}
