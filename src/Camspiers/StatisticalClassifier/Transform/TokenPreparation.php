<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;

class TokenPreparation
{
    /**
     * @var \Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface
     */
    protected $tokenizer;
    /**
     * @var \Camspiers\StatisticalClassifier\Normalizer\NormalizerInterface
     */
    protected $normalizer;
    /**
     * @param TokenizerInterface $tokenizer
     * @param NormalizerInterface $normalizer
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        NormalizerInterface $normalizer
    ) {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
    }

    public function __invoke($data)
    {
        foreach ($data as $category => $documents) {
            foreach ($documents as $index => $document) {
                $data[$category][$index] = $this->normalizer->normalize(
                    $this->tokenizer->tokenize(
                        $document
                    )
                );
            }
        }
        
        return $data;
    }
} 