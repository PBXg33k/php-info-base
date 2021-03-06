<?php

use Mock\Service\ServiceA\Service;

class BaseServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    protected $service;

    protected function setUp()
    {
        $this->service = new Service();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testClientGetterSetter()
    {
        $client = $this->createMock(\GuzzleHttp\ClientInterface::class);

        $this->assertEquals($client, $this->service->setClient($client)->getClient());
    }

    public function testApiClientGetterSetter()
    {
        $apiClient = 'This can be anything really';

        $this->assertEquals($apiClient, $this->service->setApiClient($apiClient)->getApiClient());
    }

    public function testConfigGetterSetter()
    {
        $config = ['test'];

        $this->assertEquals($config, $this->service->setConfig($config)->getConfig());
    }

    public function testInitializedGetterSetter()
    {
        $initialized = true;

        $this->assertEquals($initialized, $this->service->setInitialized($initialized)->isInitialized());
    }
}
