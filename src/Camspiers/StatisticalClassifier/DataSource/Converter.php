<?php

namespace Camspiers\StatisticalClassifier\DataSource;

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
