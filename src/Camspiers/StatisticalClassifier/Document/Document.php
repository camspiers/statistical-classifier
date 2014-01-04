<?php
namespace Camspiers\StatisticalClassifier\Document;

use Camspiers\StatisticalClassifier\Normalizer\Token;
use Camspiers\StatisticalClassifier\Tokenizer\Word;

class Document
    extends \ArrayObject
    implements DocumentInterface
{
    /**
     * @var callable $normalizer
     */
    public static $documentNormalizer;

    /**
     * @var callable $tokenizer
     */
    public static $tokenizer;

    /**
     * @var callable $normalizer
     */
    public static $tokenNormalizer;

    /**
     * @param  string|array|Traversable  $data
     * @throws \InvalidArgumentException
     */
    public function __construct($data = null)
    {
        parent::setFlags(parent::ARRAY_AS_PROPS);

        if (! empty($data)) {
            if (is_scalar($data)) {
                if (is_callable(static::$documentNormalizer)) {
                    $data = call_user_func(static::$documentNormalizer, $data);
                }

                $data = call_user_func(static::$tokenizer ?: new Word(), $data);
            } elseif (! is_array($data) and ! $data instanceof \Traversable) {
                $type = gettype($data);
                throw new \InvalidArgumentException("Data must be either a string or an array or a traversable, $type given.");
            }

            $data = array_count_values((array) $data);

            $this->exchangeArray($data);
        }
    }

    public function offsetSet($token, $count)
    {
        if (is_callable(static::$tokenNormalizer)) {
            $token = call_user_func(static::$tokenNormalizer, $token);
        }

        parent::offsetSet($token, $count);
    }

    /**
     * @param callable $normalizer
     */
    public static function setDocumentNormalizer(callable $normalizer)
    {
        static::$documentNormalizer = $normalizer;
    }

    /**
     * @param callable $tokenizer
     */
    public static function setTokenizer(callable $tokenizer)
    {
        static::$tokenizer = $tokenizer;
    }

    /**
     * @param callable $normalizer
     */
    public static function setTokenNormalizer(callable $normalizer)
    {
        static::$tokenNormalizer = $normalizer;
    }
}
