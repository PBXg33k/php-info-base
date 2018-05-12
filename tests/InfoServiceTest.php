<?php
use Mock\TestService;


class InfoServiceTest extends PHPUnit_Framework_TestCase
{
    const YAML_NAMESPACE = 'info_service';

    const METHOD_IS_INITIALIZED = 'isInitialized';

    /**
     * @var TestService
     */
    protected $infoService;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $config;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $clientMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $serviceMock;

    public function setUp()
    {
        $clientMock = $this->clientMock = $this->createMock(\GuzzleHttp\Client::class);

        $this->config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/../src/Resources/config/config.yml'));
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE]);
        $this->infoService->setClient($clientMock);

    }
    public function testConfigWithoutInitService()
    {
        unset($this->config[self::YAML_NAMESPACE]['init_services']);
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE], false);
    }
    public function testServiceConfigurationExceptionOnConstruct()
    {
        $this->expectException(\Pbxg33k\InfoBase\Exception\ServiceConfigurationException::class);
        unset($this->config[self::YAML_NAMESPACE]['services']);
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE]);
    }

    public function testPostContstructInit()
    {
        unset($this->config[self::YAML_NAMESPACE]['init_services']);
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE], false);
        $this->infoService->initializeService(new Mock\Service\ServiceA\Service());
    }

    public function testLoadService()
    {
        $this->infoService->loadService('ServiceA', true);
    }

    public function testExceptionOnNonExistingService()
    {
        $this->expectException(\Exception::class);
        $this->infoService->loadService('i do not exist lol');
    }
    public function testLoadServicesMethod()
    {
        $this->infoService->loadServices();
    }
    public function testGetNonExistingService()
    {
        $this->assertNull($this->infoService->getService('i do not exist'));
    }
    public function testRemoveService()
    {
        $this->assertInstanceOf('Mock\Service\ServiceA\Service', $this->infoService->getService('servicea'));
        $this->infoService->removeService('servicea');
        $this->assertNull($this->infoService->getService('servicea'));
    }

    public function testDefaults()
    {
        unset($this->config[self::YAML_NAMESPACE]['service_configuration']['servicea']);
        $this->musicInfo = new TestService($this->config[self::YAML_NAMESPACE]);
    }
    public function testPreferredServiceConfig()
    {
        $this->assertEquals(
            $this->infoService->getService(strtolower($this->config[self::YAML_NAMESPACE]['preferred_order'][0])),
            $this->infoService->getPreferredService()
        );
    }

    public function testDoSearch()
    {
        $this->addServiceMockbuilder(['typeArg', self::METHOD_IS_INITIALIZED, 'search']);

        $this->serviceMock->expects($this->once())
            ->method('typeArg')
            ->willReturnSelf();

        $this->serviceMock->expects($this->once())
            ->method('search')
            ->willReturn('testData');

        $this->infoService->addService($this->serviceMock, 'mock');

        $result = $this->infoService->doSearch('searchArg', 'typeArg', 'mock');

        $this->assertEquals(false, $result->getServiceResult('mock')->isError());
        $this->assertEquals('testData', $result->getServiceResult('mock')->getData());
    }

    /**
     * @expectedException \Pbxg33k\InfoBase\Exception\ServiceConfigurationException
     */
    public function testServiceConfigurationExceptionIfSearchTypeIsNotFound()
    {
        $this->addServiceMockbuilder([self::METHOD_IS_INITIALIZED]);

        $this->infoService->doSearch('fail', 'foo', 'mock');
    }

    private function addServiceMockbuilder($methods)
    {
        $this->serviceMock = $this->getMockBuilder(\Pbxg33k\InfoBase\Model\IService::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMockForAbstractClass();

        if(in_array(self::METHOD_IS_INITIALIZED, $methods)) {
            $this->serviceMock->expects($this->once())
                ->method(self::METHOD_IS_INITIALIZED)
                ->willreturn(true);
        }

        $this->infoService->addService($this->serviceMock, 'mock');
    }
}
