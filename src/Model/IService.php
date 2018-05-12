<?php
namespace Pbxg33k\InfoBase\Model;

use GuzzleHttp\ClientInterface;

interface IService
{
    /**
     * @param ClientInterface $client
     *
     * @return mixed
     */
    public function setClient(ClientInterface $client);

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * @param $config
     *
     * @return mixed
     */
    public function setConfig($config = null);

    /**
     * @return mixed
     */
    public function getConfig();

    /**
     * Set the API Library client
     *
     * @param $apiClient
     *
     * @return mixed
     */
    public function setApiClient($apiClient);

    /**
     * Get the API Library client
     *
     * @return mixed
     */
    public function getApiClient();

    public function setInitialized(bool $initialized);

    /**
     * Service specific initializer
     *
     * Construct your API client in this method.
     * It is set to be the method that is called by Symfony's Service Loader
     *
     * @param array $config
     *
     * @return mixed
     */
    public function init($config = []);
}