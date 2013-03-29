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

use PDO;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class PDOQuery extends DataArray
{
    /**
     * The pdo connection object
     * @var PDO
     */
    private $pdo;
    /**
     * The category of the query
     * @var string
     */
    private $category;
    /**
     * The query to run
     * @var string
     */
    private $query;
    /**
     * The column to use for the document
     * @var string
     */
    private $documentColumn;
    /**
     * Creates the data source with the query details
     * @param string $category       Category of the query
     * @param PDO    $pdo            The PDO connection object
     * @param string $query          The query to run
     * @param string $documentColumn The column of the document
     */
    public function __construct(
        $category,
        PDO $pdo,
        $query,
        $documentColumn
    ) {
        $this->category = $category;
        $this->pdo = $pdo;
        $this->query = $query;
        $this->documentColumn = $documentColumn;
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        $query = $this->pdo->query($this->query);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $documents = array();
        while ($row = $query->fetch()) {
            $documents[] = array(
                'category' => $this->category,
                'document' => $row[$this->documentColumn]
            );
        }

        return $documents;
    }
}
