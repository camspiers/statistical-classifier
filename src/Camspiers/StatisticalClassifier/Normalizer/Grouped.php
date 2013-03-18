<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier\Normalizer;

use RuntimeException;

class Grouped implements NormalizerInterface
{
    protected $normalizers = array();

    public function __construct(array $normalizers = array())
    {
        if (count($normalizers) === 0) {
            throw new RuntimeException('A group of normalizers must contain at least one normalizer');
        }
        foreach ($normalizers as $normalizer) {
            $this->addNormalizer($normalizer);
        }
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }

    public function normalize(array $tokens)
    {
        foreach ($this->normalizers as $normalizer) {
            $tokens = $normalizer->normalize($tokens);
        }

        return $tokens;
    }
}
