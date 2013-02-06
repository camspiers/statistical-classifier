<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class PDOQuery
{
    private $pdo;
    private $category;
    private $query;
    private $documentColumn;

    public function __construct(
        $category,
        \PDO $pdo,
        $query,
        $documentColumn
    ) {
        $this->category = $category;
        $this->pdo = $pdo;
        $this->query = $query;
        $this->documentColumn = $documentColumn;
    }

    public function read()
    {
        $query = $this->pdo->query($this->query);
        $query->setFetchMode(\PDO::FETCH_ASSOC);
        $data = array();
        while ($row = $query->fetch()) {
            $data[] = $row[$this->documentColumn];
        }

        return $data;
    }

    public function getCategory()
    {
        return $this->category;
    }

}
