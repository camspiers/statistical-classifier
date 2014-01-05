<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer\Token;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 * @see https://github.com/hthetiot/php-stemmer.git
 */
class PhpStemmer implements NormalizerInterface
{
    /**
     * Available languages.
     *
     * @var array
     */
    protected $availableLanguages = array('danish', 'dutch', 'english', 'finnish', 'french', 'german', 'hungarian',
                                          'italian', 'norwegian', 'porter', 'portuguese', 'romanian', 'russian',
                                          'spanish', 'swedish', 'turkish');

    /**
     * Charset.
     *
     * @var string
     */
    protected $charset;

    /**
     * Lang.
     *
     * @var string
     */
    protected $lang;

    /**
     * @param string $lang
     * @param string $charset
     */
    public function __construct($lang, $charset = 'utf-8')
    {
        $lang = strtolower($lang);

        if (! in_array($lang, $this->availableLanguages)) {
            throw new \InvalidArgumentException("Invalid language $lang");
        }

        $this->charset = strtoupper(str_replace('-', '_', $charset));;
        $this->lang    = $lang;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(array $tokens)
    {
        foreach ($tokens as $k => $token) {
            $tokens[$k] = stemword($token, $this->lang, $this->charset);
        }

        return $tokens;
    }
}
