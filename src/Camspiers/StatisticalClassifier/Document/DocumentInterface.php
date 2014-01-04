<?php
namespace Camspiers\StatisticalClassifier\Document;

interface DocumentInterface
{
    /**
     * @param callable $normalizer
     */
    public static function setDocumentNormalizer(callable $normalizer);

    /**
     * @param callable $tokenizer
     */
    public static function setTokenizer(callable $tokenizer);

    /**
     * @param callable $normalizer
     */
    public static function setTokenNormalizer(callable $normalizer);
}
