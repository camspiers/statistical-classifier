<?php

namespace Camspiers\StatisticalClassifier\DataSource;

interface DataSourceInterface
{
    public function write();
    public function read();
    public function getData();
    public function getCategories();
    public function hasCategory($category);
    public function addCategory($category);
    public function addDocument($category, $document);
    public function getDocumentCount();
}
