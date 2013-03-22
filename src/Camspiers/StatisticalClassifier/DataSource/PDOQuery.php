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

use PDO as BasePDO;

/**
 * @author Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PDOQuery
{
    private $pdo;
    private $category;
    private $query;
    private $documentColumn;

    public function __construct(
        $category,
        BasePDO $pdo,
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
        $query->setFetchMode(BasePDO::FETCH_ASSOC);
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
