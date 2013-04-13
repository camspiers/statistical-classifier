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
class CSV extends DataArray
{
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
                'file',
                'document_columns',
                'category_column'
            )
        );

        $resolver->setOptional(
            array(
                'limit',
                'delimiter',
                'enclosure',
                'escape',
                'category_modifier'
            )
        );

        $resolver->setDefaults(
            array(
                'limit' => false,
                'length' => 0,
                'delimiter' => ',',
                'enclosure' => '"',
                'escape' => '\\',
                'category_modifier' => false
            )
        );

        $resolver->setAllowedTypes(
            array(
                'file' => 'string',
                'document_columns' => 'array',
                'category_column' => 'string',
                'length' => 'int',
                'delimiter' => 'string',
                'enclosure' => 'string',
                'escape' => 'string'
            )
        );
    }
    /**
     * @{inheritdoc}
     */
    public function read()
    {
        $entries = array();

        if (file_exists($this->options['file'])) {
            $handle = fopen($this->options['file'], 'r');

            $columns = $this->readColumns($handle);
            $this->checkColumns($columns);

            $columnTotal = count($columns);
            $entryCount = 0;
            $hasModifier = is_callable($this->options['category_modifier']);

            while (true) {
                if ($this->options['limit'] && $this->options['limit'] < $entryCount) {
                    break;
                }

                if (($csvData = $this->readLine($handle)) === false) {
                    break;
                }

                if ($columnTotal !== count($csvData)) {
                    continue;
                }

                $document = array();

                foreach ($this->options['document_columns'] as $column) {
                    $document[] = $csvData[$columns[$column]];
                }

                $category = $csvData[$columns[$this->options['category_column']]];

                if ($hasModifier) {
                    $category = $this->options['category_modifier']($category);
                }

                $entries[] = array(
                    'document' => implode(' ', $document),
                    'category' => $category
                );

                $entryCount++;
            }

            fclose($handle);
        }

        return $entries;
    }
    /**
     * @param $handle
     * @return array
     * @throws \RuntimeException
     */
    protected function readColumns($handle)
    {
        if ($handle === false) {
            throw new \RuntimeException("Could not read file '{$this->options['file']}'");
        }

        $columns = $this->readLine($handle);

        if ($columns === false) {
            throw new \RuntimeException("Failed to determine csv columns");
        }

        /**
         * Result:
         * array(
         *    'ColumnName1' => 0,
         *    'ColumnName2' => 1
         * )
         */
        $columns = array_flip($columns);

        return $columns;
    }
    /**
     * @param $columns
     * @return array
     * @throws \RuntimeException
     */
    protected function checkColumns($columns)
    {
        $neededColumns = $this->options['document_columns'];
        $neededColumns[] = $this->options['category_column'];
        foreach ($neededColumns as $column) {
            if (!array_key_exists($column, $columns)) {
                throw new \RuntimeException("Column '$column' doesn't exist in the csv");
            }
        }
    }
    /**
     * @param $handle
     * @return array
     */
    protected function readLine($handle)
    {
        return fgetcsv(
            $handle,
            $this->options['length'],
            $this->options['delimiter'],
            $this->options['enclosure'],
            $this->options['escape']
        );
    }
    /**
     * @{inheritdoc}
     */
    public function write()
    {
    }
}
