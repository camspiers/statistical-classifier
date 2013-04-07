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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Directory extends DataArray
{
    /**
     * Used for docs in category folder
     */
    const MODE_DIRECTORY_AS_CATEGORY = 0;
    /**
     * Used for docs named be category
     */
    const MODE_DOCUMENT_AS_CATEGORY = 1;
    /**
     * Stores the configuration options
     * @var array
     */
    protected $options;
    /**
     * Creates the object from an array of options
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
    /**
     * Sets the configuration for the options resolver
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            array(
                'directory'
            )
        );

        $resolver->setDefaults(
            array(
                'mode' => self::MODE_DIRECTORY_AS_CATEGORY,
                'include' => array(),
                'limit' => null
            )
        );

        $resolver->setAllowedValues(
            array(
                'mode' => array(
                    self::MODE_DOCUMENT_AS_CATEGORY,
                    self::MODE_DIRECTORY_AS_CATEGORY
                )
            )
        );

        $resolver->setAllowedTypes(
            array(
                'directory' => 'string',
                'mode' => 'int',
                'include' => 'array'
            )
        );
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        $data = array();
        if (file_exists($this->options['directory'])) {
            $pattern = $this->options['mode'] == self::MODE_DIRECTORY_AS_CATEGORY ? '/*' : '';
            if (is_array($this->options['include']) && count($this->options['include']) !== 0) {
                $files = array();
                foreach ($this->options['include'] as $include) {
                    $files = array_merge(
                        $files,
                        array_slice(
                            glob(
                                "{$this->options['directory']}/{$include}{$pattern}",
                                GLOB_NOSORT
                            ),
                            0,
                            $this->options['limit']
                        )
                    );
                }
            } else {
                $files = array_slice(
                    glob("{$this->options['directory']}{$pattern}/*", GLOB_NOSORT),
                    0,
                    $this->options['limit']
                );
            }
            foreach ($files as $filename) {
                if (is_file($filename)) {
                    if ($this->options['mode'] === self::MODE_DIRECTORY_AS_CATEGORY) {
                        $categoryPath = dirname($filename);
                    } else {
                        $categoryPath = $filename;
                    }
                    $data[] = array(
                        'category' => basename($categoryPath),
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
            if (!file_exists($this->options['directory'] . '/' . $category)) {
                mkdir($this->options['directory'] . '/' . $category);
            }
            foreach ($documents as $document) {
                file_put_contents(
                    $this->options['directory'] . '/' . $category . '/' . md5($document),
                    $document
                );
            }
        }
    }
}
