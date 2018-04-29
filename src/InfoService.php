<?php
namespace Pbxg33k\InfoBase;

use Doctrine\Common\Collections\ArrayCollection;
use Guzzle\Http\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Pbxg33k\InfoBase\Exception\ServiceConfigurationException;
use Pbxg33k\InfoBase\Model\IService;
use Pbxg33k\InfoBase\Model\RequestError;
use Pbxg33k\InfoBase\Model\Result;
use Pbxg33k\InfoBase\Service\BaseService;
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
     * @param $config
     * @throws ServiceConfigurationException
     * @throws \Exception
     */
    public function __construct($config)
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
             *
             *
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
     * @return null|BaseService
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
     * @param BaseService $service
     *
     * @return BaseService
     * @throws ServiceConfigurationException
     */
    public function initializeService(BaseService $service)
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
     * @return BaseService|null
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
     * @return ArrayCollection[Result]
     * @throws \Exception
     */
    public function doSearch($argument, $type, $servicesArg = null)
    {
        $services = $this->_prepareSearch($servicesArg);
        $results = new ArrayCollection();

        foreach ($services as $serviceKey => $service) {
            $methodName = $this->getMethodName($type);

            if (!method_exists($service, $methodName)) {
                throw new \Exception(sprintf('Method (%s) not found in %s', $methodName, get_class($service)));
            }

            $result = new Result();
            try {
                $results->set($serviceKey, $result->setData($service->{$methodName}()->getByName($argument))->setError(false));
            } catch (RequestException $exception) {
                $results->set($serviceKey, $result
                    ->setError(true)
                    ->setData(
                        (new RequestError())
                            ->setArguments($argument)
                            ->setRequest($exception->getRequest())
                            ->setException($exception)
                            ->setService($serviceKey)
                    ));
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                $results->set($serviceKey, $result
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
                    throw new \Exception(sprintf('Service (%s) cannot be found', $service));
                }
            }
        } elseif (is_string($servicesArg) && $loadedService = $this->getService($servicesArg)) {
            $services->set($servicesArg, $loadedService);

        }

        return $services;
    }
}