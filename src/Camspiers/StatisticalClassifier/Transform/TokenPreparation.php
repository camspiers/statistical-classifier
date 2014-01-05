<?php

namespace Camspiers\StatisticalClassifier\Transform;

use Camspiers\StatisticalClassifier\Normalizer\Token;
use Camspiers\StatisticalClassifier\Normalizer\Document;
use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;

class TokenPreparation
{
    /**
     * @var \Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface
     */
    protected $tokenizer;
    /**
     * @var \Camspiers\StatisticalClassifier\Normalizer\Document\NormalizerInterface
     */
    protected $documentNormalizer;
    /**
     * @var \Camspiers\StatisticalClassifier\Normalizer\Token\NormalizerInterface
     */
    protected $tokenNormalizer;
    /**
     * @param \Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface $tokenizer
     * @param \Camspiers\StatisticalClassifier\Normalizer\Document\NormalizerInterface $documentNormalizer
     * @param \Camspiers\StatisticalClassifier\Normalizer\Token\NormalizerInterface $tokenNormalizer
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        Document\NormalizerInterface $documentNormalizer = null,
        Token\NormalizerInterface $tokenNormalizer = null
    ) {
        $this->tokenizer = $tokenizer;
        $this->documentNormalizer = $documentNormalizer;
        $this->tokenNormalizer = $tokenNormalizer;
    }

    public function __invoke($data)
    {
        foreach ($data as $category => $documents) {
            foreach ($documents as $index => $document) {
                if ($this->documentNormalizer) {
                    $document = $this->documentNormalizer->normalize($document);
                }
                
                $tokens = $this->tokenizer->tokenize($document);
                
                if ($this->tokenNormalizer) {
                    $tokens = $this->tokenNormalizer->normalize($tokens);
                }
                
                $data[$category][$index] = $tokens;
            }
        }
        
        return $data;
    }
} 