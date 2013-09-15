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
class Json extends DataArray
{
    /**
     * The filename of the json file
     * @var string
     */
    private $filename;
    /**
     * Creates the object from the filename
     * @param string $filename The filename of the json file
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        if (file_exists($this->filename)) {
            $data = json_decode(file_get_contents($this->filename), true);
            if (is_array($data)) {
                return $data;
            }
        }

        return array();
    }
    /**
     * @{inheritdoc}
     */
    public function write()
    {
        $data = array();
        foreach ($this->data as $category => $documents) {
            foreach ($documents as $document) {
                $data[] = array(
                    'category' => $category,
                    'document' => $document
                );
            }
        }
        file_put_contents($this->filename, json_encode($data));
    }
}
