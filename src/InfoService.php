<?php
namespace Pbxg33k\InfoBase;

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Pbxg33k\InfoBase\Exception\ServiceConfigurationException;
use Pbxg33k\InfoBase\Exception\ServiceException;
use Pbxg33k\InfoBase\Model\IService;
use Pbxg33k\InfoBase\Model\RequestError;
use Pbxg33k\InfoBase\Model\SearchResult;
use Pbxg33k\InfoBase\Model\ServiceResult;
use Pbxg33k\Traits\PropertyTrait;

abstract class InfoService
{
    use PropertyTrait;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var ArrayCollection
     */
    protected $services;

    /**
     * @var array
     */
    protected $config;

    /**
     * Supported Services
     *
     * @var array
     */
    protected $supportedServices = [];

    /**
     * InfoService constructor.
     *
     * @param array $config
     * @throws ServiceConfigurationException
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->services = new ArrayCollection();
        $this->setClient(
            new Client($config['defaults']['guzzle'])
        );

        if (isset($config['services'])) {
            foreach ($config['services'] as $service) {
                if (!isset($config['init_services'])) {
                    $config['init_services'] = null;
                }
                $this->loadService($service, $config['init_services']);
                $this->supportedServices[] = $service;
            }
        } else {
            throw new ServiceConfigurationException("musicinfo.services is required");
        }
    }

    /**
     * @param ClientInterface $client
     *
     * @return $this
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Load service
     *
     * @param $service
     * @param $init
     *
     * @return IService
     *
     * @throws \Exception
     */
    public function loadService($service, $init = false)
    {
        $fqcn = implode('\\', [$this->getNamespace() , 'Service', $service, 'Service']);
        if (class_exists($fqcn)) {
            /**
             * @var IService $client
             */
            $client = new $fqcn();
            $client->setConfig($this->mergeConfig($service));
            $client->setClient($this->getClient());
            if ($init === true) {
                $client->init();
            }
            $this->addService($client, $service);

            return $service;
        } else {
            throw new \Exception('Service class does not exist: ' . $service . ' (' . $fqcn . ')');
        }
    }

    /**
     * A temp method which should return the namespace.
     * Ideally this should be automagically figured out.
     *
     * @return string
     */
    abstract protected function getNamespace();

    /**
     * Merge shared config with service specific configuration
     *
     * @param $service
     *
     * @return array
     */
    public function mergeConfig($service)
    {
        $service = strtolower($service);
        if (isset($this->config['service_configuration'][$service])) {
            $config = array_merge(
                $this->config['defaults'],
                $this->config['service_configuration'][$service]
            );

            return $config;
        } else {
            return $this->config['defaults'];
        }
    }

    /**
     * Load all services
     *
     * @param bool $initialize
     *
     * @return ArrayCollection
     * @throws \Exception
     */
    public function loadServices($initialize = false)
    {
        foreach ($this->supportedServices as $service) {
            $this->loadService($service, $initialize);
        }

        return $this->getServices();
    }

    /**
     * @param IService $service
     * @param               $key
     *
     * @return $this
     */
    public function addService(IService $service, $key)
    {
        $this->services[strtolower($key)] = $service;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param $key
     *
     * @return null|IService
     * @throws ServiceConfigurationException
     */
    public function getService($key)
    {
        $key = strtolower($key);
        if (isset($this->services[$key])) {
            return $this->initializeService($this->services[$key]);
        } else {
            return null;
        }
    }

    /**
     * @param IService $service
     *
     * @return IService
     * @throws ServiceConfigurationException
     */
    public function initializeService(IService $service)
    {
        if (!$service->isInitialized()) {
            $service->init();
        }

        return $service;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function removeService($key)
    {
        if (isset($this->services[$key])) {
            unset($this->services[$key]);
        }

        return $this;
    }

    /**
     * @return null|IService
     * @throws ServiceConfigurationException
     */
    public function getPreferredService()
    {
        return $this->getService($this->config['preferred_order'][0]);
    }

    /**
     * Perform Multi-service search
     *
     * @param $argument
     * @param $type
     * @param null     $servicesArg
     *
     * @return SearchResult
     * @throws \Exception
     */
    public function doSearch($argument, $type, $servicesArg = null)
    {
        $services = $this->_prepareSearch($servicesArg);
        $results = new SearchResult();

        foreach ($services as $serviceKey => $service) {
            $methodName = $this->getMethodName($type);

            if (!method_exists($service, $methodName)) {
                throw new ServiceConfigurationException(sprintf('Method (%s) not found in %s', $methodName, get_class($service)));
            }

            $result = new ServiceResult();
            try {
                $results->setResult($serviceKey, $result->setData($service->{$methodName}()->search($argument))->setError(false));
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                $results->setResult($serviceKey, $result
                    ->setError(true)
                    ->setData(
                        (new RequestError())
                            ->setArguments($argument)
                            ->setRequest($exception->getRequest())
                            ->setException($exception)
                            ->setService($serviceKey)
                            ->setRequest($exception->getRequest())
                    ));
            }
        }

        return $results;
    }

    /**
     * Return an arraycollection with (loaded) services
     *
     * @param mixed $servicesArg
     *
     * @return ArrayCollection
     * @throws \Exception
     */
    protected function _prepareSearch($servicesArg = null)
    {
        $services = new ArrayCollection();

        if (null === $servicesArg) {
            $services = $this->getServices();
        } elseif (is_array($servicesArg)) {
            foreach ($servicesArg as $service) {
                if (is_string($service) && $loadedService = $this->getService($service)) {
                    $services->set($service, $loadedService);
                } else {
                    throw new ServiceException(sprintf('Service (%s) cannot be found', $service));
                }
            }
        } elseif (is_string($servicesArg) && $loadedService = $this->getService($servicesArg)) {
            $services->set($servicesArg, $loadedService);
        }

        return $services;
    }
}