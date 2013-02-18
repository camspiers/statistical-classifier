<?php

namespace Camspiers\StatisticalClassifier\DataSource;

class Directory extends DataArray
{
    private $directory;
    private $include;

    public function __construct($directory, array $include = null)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        $this->directory = realpath($directory);
        $this->include = $include;
        parent::__construct($this->read());
    }

    public function read()
    {
        $data = array();
        if (file_exists($this->directory)) {
            if (is_array($this->include)) {
                $files = array();
                foreach ($this->include as $include) {
                    $files = array_merge($files, glob("$this->directory/$include/*", GLOB_NOSORT));
                }
            } else {
                $files = glob($this->directory . '/*/*', GLOB_NOSORT);
            }
            foreach ($files as $filename) {
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
