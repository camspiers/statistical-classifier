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
        $this->services['classifier.naive_bayes'] = $instance = new \Camspiers\StatisticalClassifier\Classifiers\NaiveBayes($this->get('classifier.data_source'), $this->get('tokenizer.word'), $this->get('normalizer.lowercase'));

        $instance->addHeuristic($this->get('heuristic.tf_threaded'));
        $instance->addHeuristic($this->get('heuristic.idf'));
        $instance->addHeuristic($this->get('heuristic.dl'));

        return $instance;
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
     * Gets the 'heuristic.dl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Heuristics\DL A Camspiers\StatisticalClassifier\Heuristics\DL instance.
     */
    protected function getHeuristic_DlService()
    {
        return $this->services['heuristic.dl'] = new \Camspiers\StatisticalClassifier\Heuristics\DL();
    }

    /**
     * Gets the 'heuristic.idf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Heuristics\IDF A Camspiers\StatisticalClassifier\Heuristics\IDF instance.
     */
    protected function getHeuristic_IdfService()
    {
        return $this->services['heuristic.idf'] = new \Camspiers\StatisticalClassifier\Heuristics\IDF();
    }

    /**
     * Gets the 'heuristic.tf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Heuristics\TF A Camspiers\StatisticalClassifier\Heuristics\TF instance.
     */
    protected function getHeuristic_TfService()
    {
        return $this->services['heuristic.tf'] = new \Camspiers\StatisticalClassifier\Heuristics\TF();
    }

    /**
     * Gets the 'heuristic.tf_threaded' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Heuristics\TFThreaded A Camspiers\StatisticalClassifier\Heuristics\TFThreaded instance.
     */
    protected function getHeuristic_TfThreadedService()
    {
        return $this->services['heuristic.tf_threaded'] = new \Camspiers\StatisticalClassifier\Heuristics\TFThreaded();
    }

    /**
     * Gets the 'normalizer.lowercase' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizers\Lowercase A Camspiers\StatisticalClassifier\Normalizers\Lowercase instance.
     */
    protected function getNormalizer_LowercaseService()
    {
        return $this->services['normalizer.lowercase'] = new \Camspiers\StatisticalClassifier\Normalizers\Lowercase();
    }

    /**
     * Gets the 'normalizer.porter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizers\Porter A Camspiers\StatisticalClassifier\Normalizers\Porter instance.
     */
    protected function getNormalizer_PorterService()
    {
        return $this->services['normalizer.porter'] = new \Camspiers\StatisticalClassifier\Normalizers\Porter();
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
