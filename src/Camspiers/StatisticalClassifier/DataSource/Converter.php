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
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Converter
{
    /**
     * The source to convert from
     * @var DataSourceInterface
     */
    private $convertFrom;
    /**
     * The source to convert to
     * @var DataSourceInterface
     */
    private $convertTo;
    /**
     * Creates the converter using to data sources
     * @param DataSourceInterface $convertFrom
     * @param DataSourceInterface $convertTo
     */
    public function __construct(DataSourceInterface $convertFrom, DataSourceInterface $convertTo)
    {
        $this->convertFrom = $convertFrom;
        $this->convertTo = $convertTo;
    }
    /**
     * run the conversion
     * @return null
     */
    public function run()
    {
        $this->convertTo->setData($this->convertFrom->getData());
        $this->convertTo->write();
    }
}
