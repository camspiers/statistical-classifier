<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class Directory extends DataArray
{
    private $directory;

    public function __construct($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        $this->directory = realpath($directory);
        parent::__construct($this->read());
    }

    public function read()
    {
        $data = array();
        if (file_exists($this->directory)) {
            foreach (glob($this->directory . '/*/*', GLOB_NOSORT) as $filename) {
                if (is_file($filename)) {
                    $dirname = basename(dirname($filename));
                    if (!isset($data[$dirname])) {
                        $data[$dirname] = array();
                    }
                    $data[$dirname][] = file_get_contents($filename);
                }
            }
        }

        return $data;
    }

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
