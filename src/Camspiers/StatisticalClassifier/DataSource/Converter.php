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
    /**
     * The source to convert from
     * @var DataSourceInterface
     */
    private $from;
    /**
     * The source to convert to
     * @var DataSourceInterface
     */
    private $to;
    /**
     * Creates the converter using to data sources
     * @param DataSourceInterface $from The source to convert from
     * @param DataSourceInterface $to   Teh source to convert to
     */
    public function __construct(DataSourceInterface $from, DataSourceInterface $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    /**
     * run the conversion
     * @return null
     */
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
