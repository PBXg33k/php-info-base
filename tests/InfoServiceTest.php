<?php
use Mock\TestService;


class InfoServiceTest extends PHPUnit_Framework_TestCase
{
    const YAML_NAMESPACE = 'info_service';

    /**
     * @var TestService
     */
    protected $infoService;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $config;

    public function setUp()
    {
        $this->config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/../src/Resources/config/config.yml'));
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE]);
    }
    public function testConfigWithoutInitService()
    {
        unset($this->config[self::YAML_NAMESPACE]['init_services']);
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE]);
    }
    public function testServiceConfigurationExceptionOnConstruct()
    {
        $this->expectException(\Pbxg33k\InfoBase\Exception\ServiceConfigurationException::class);
        unset($this->config[self::YAML_NAMESPACE]['services']);
        $this->infoService = new TestService($this->config[self::YAML_NAMESPACE]);
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

}
