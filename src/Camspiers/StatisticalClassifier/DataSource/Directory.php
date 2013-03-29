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
class Directory extends DataArray
{
    /**
     * The directory to find the documents and categories in
     * @var string
     */
    private $directory;
    /**
     * An array of directories to get documents from
     * @var array
     */
    private $include;
    /**
     * Creates the object from a directory path and an optional array of included directories
     * @param string $directory The path to the directory
     * @param array  $include   An array of included directories
     */
    public function __construct($directory, array $include = null)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        $this->directory = realpath($directory);
        $this->include = $include;
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        $data = array();
        if (file_exists($this->directory)) {
            if (is_array($this->include) && count($this->include) !== 0) {
                $files = array();
                foreach ($this->include as $include) {
                    $files = array_merge($files, glob("$this->directory/$include/*", GLOB_NOSORT));
                }
            } else {
                $files = glob($this->directory . '/*/*', GLOB_NOSORT);
            }
            foreach ($files as $filename) {
                if (is_file($filename)) {
                    $data[] = array(
                        'category' => basename(dirname($filename)),
                        'document' => file_get_contents($filename)
                    );
                }
            }
        }

        return $data;
    }
    /**
     * @{inheritdoc}
     */
    public function write()
    {
        foreach ($this->data as $category => $documents) {
            if (!file_exists($this->directory . '/' . $category)) {
                mkdir($this->directory . '/' . $category);
            }
            foreach ($documents as $document) {
                file_put_contents(
                    $this->directory . '/' . $category . '/' . md5($document),
                    $document
                );
            }
        }
    }
}
