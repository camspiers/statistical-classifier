<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * StatisticalClassifierServiceContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class StatisticalClassifierServiceContainer extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array();
        $this->scopeChildren = array();
    }

    /**
     * Gets the 'classifier.data_source' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getClassifier_DataSourceService()
    {
        throw new RuntimeException('You have requested a synthetic service ("classifier.data_source"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'classifier.naive_bayes' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Classifiers\NaiveBayes A Camspiers\StatisticalClassifier\Classifiers\NaiveBayes instance.
     */
    protected function getClassifier_NaiveBayesService()
    {
        return $this->services['classifier.naive_bayes'] = new \Camspiers\StatisticalClassifier\Classifiers\NaiveBayes($this->get('classifier.data_source'), $this->get('tokenizer.word'));
    }

    /**
     * Gets the 'converter.converter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\DataSource\Converter A Camspiers\StatisticalClassifier\DataSource\Converter instance.
     */
    protected function getConverter_ConverterService()
    {
        return $this->services['converter.converter'] = new \Camspiers\StatisticalClassifier\DataSource\Converter($this->get('converter.from'), $this->get('converter.to'));
    }

    /**
     * Gets the 'converter.from' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getConverter_FromService()
    {
        throw new RuntimeException('You have requested a synthetic service ("converter.from"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'converter.to' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getConverter_ToService()
    {
        throw new RuntimeException('You have requested a synthetic service ("converter.to"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'normalizer.lowercase' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Nomalizers\Lowercase A Camspiers\StatisticalClassifier\Nomalizers\Lowercase instance.
     */
    protected function getNormalizer_LowercaseService()
    {
        return $this->services['normalizer.lowercase'] = new \Camspiers\StatisticalClassifier\Nomalizers\Lowercase();
    }

    /**
     * Gets the 'normalizer.porter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Nomalizers\Porter A Camspiers\StatisticalClassifier\Nomalizers\Porter instance.
     */
    protected function getNormalizer_PorterService()
    {
        return $this->services['normalizer.porter'] = new \Camspiers\StatisticalClassifier\Nomalizers\Porter();
    }

    /**
     * Gets the 'tokenizer.word' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Tokenizers\Word A Camspiers\StatisticalClassifier\Tokenizers\Word instance.
     */
    protected function getTokenizer_WordService()
    {
        return $this->services['tokenizer.word'] = new \Camspiers\StatisticalClassifier\Tokenizers\Word();
    }
}
