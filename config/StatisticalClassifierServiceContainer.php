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
        $this->parameters = $this->getDefaultParameters();

        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array();
        $this->scopeChildren = array();
    }

    /**
     * Gets the 'cache' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return CacheCache\Cache A CacheCache\Cache instance.
     */
    protected function getCacheService()
    {
        return $this->services['cache'] = new \CacheCache\Cache($this->get('cache.backend'));
    }

    /**
     * Gets the 'cache.backend' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return CacheCache\Backends\File A CacheCache\Backends\File instance.
     */
    protected function getCache_BackendService()
    {
        return $this->services['cache.backend'] = new \CacheCache\Backends\File(array('dir' => './resources/', 'file_extension' => '.idx'));
    }

    /**
     * Gets the 'classifier.naive_bayes' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Classifier\NaiveBayes A Camspiers\StatisticalClassifier\Classifier\NaiveBayes instance.
     */
    protected function getClassifier_NaiveBayesService()
    {
        return $this->services['classifier.naive_bayes'] = new \Camspiers\StatisticalClassifier\Classifier\NaiveBayes($this->get('data_source.data_source'), $this->get('index.cached_index'), $this->get('tokenizer.word'), $this->get('normalizer.lowercase'));
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
     * Gets the 'data_source.data_source' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getDataSource_DataSourceService()
    {
        throw new RuntimeException('You have requested a synthetic service ("data_source.data_source"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'index.cached_index' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Index\CachedIndex A Camspiers\StatisticalClassifier\Index\CachedIndex instance.
     */
    protected function getIndex_CachedIndexService()
    {
        return $this->services['index.cached_index'] = new \Camspiers\StatisticalClassifier\Index\CachedIndex('GenericClassifierIndex', $this->get('cache'));
    }

    /**
     * Gets the 'index.index' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Index\Index A Camspiers\StatisticalClassifier\Index\Index instance.
     */
    protected function getIndex_IndexService()
    {
        return $this->services['index.index'] = new \Camspiers\StatisticalClassifier\Index\Index();
    }

    /**
     * Gets the 'logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Logger A Monolog\Logger instance.
     */
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = new \Monolog\Logger('Default');

        $instance->pushHandler($this->get('logger.stream'));

        return $instance;
    }

    /**
     * Gets the 'logger.stream' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Handler\StreamHandler A Monolog\Handler\StreamHandler instance.
     */
    protected function getLogger_StreamService()
    {
        return $this->services['logger.stream'] = new \Monolog\Handler\StreamHandler('logs/classifier.log', 100);
    }

    /**
     * Gets the 'normalizer.lowercase' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Lowercase A Camspiers\StatisticalClassifier\Normalizer\Lowercase instance.
     */
    protected function getNormalizer_LowercaseService()
    {
        return $this->services['normalizer.lowercase'] = new \Camspiers\StatisticalClassifier\Normalizer\Lowercase();
    }

    /**
     * Gets the 'normalizer.porter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Porter A Camspiers\StatisticalClassifier\Normalizer\Porter instance.
     */
    protected function getNormalizer_PorterService()
    {
        return $this->services['normalizer.porter'] = new \Camspiers\StatisticalClassifier\Normalizer\Porter();
    }

    /**
     * Gets the 'tokenizer.word' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Tokenizer\Word A Camspiers\StatisticalClassifier\Tokenizer\Word instance.
     */
    protected function getTokenizer_WordService()
    {
        return $this->services['tokenizer.word'] = new \Camspiers\StatisticalClassifier\Tokenizer\Word();
    }

    /**
     * Gets the 'transform.dl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\DL A Camspiers\StatisticalClassifier\Transform\DL instance.
     */
    protected function getTransform_DlService()
    {
        return $this->services['transform.dl'] = new \Camspiers\StatisticalClassifier\Transform\DL();
    }

    /**
     * Gets the 'transform.idf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\IDF A Camspiers\StatisticalClassifier\Transform\IDF instance.
     */
    protected function getTransform_IdfService()
    {
        return $this->services['transform.idf'] = new \Camspiers\StatisticalClassifier\Transform\IDF();
    }

    /**
     * Gets the 'transform.tf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\TF A Camspiers\StatisticalClassifier\Transform\TF instance.
     */
    protected function getTransform_TfService()
    {
        return $this->services['transform.tf'] = new \Camspiers\StatisticalClassifier\Transform\TF();
    }

    /**
     * Gets the 'transform.tf_threaded' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\TFThreaded A Camspiers\StatisticalClassifier\Transform\TFThreaded instance.
     */
    protected function getTransform_TfThreadedService()
    {
        return $this->services['transform.tf_threaded'] = new \Camspiers\StatisticalClassifier\Transform\TFThreaded();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        $name = strtolower($name);

        return isset($this->parameters[$name]) || array_key_exists($name, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }
    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'index.cached_index_name' => 'GenericClassifierIndex',
            'cache.backend.options' => array(
                'dir' => './resources/',
                'file_extension' => '.idx',
            ),
        );
    }
}
