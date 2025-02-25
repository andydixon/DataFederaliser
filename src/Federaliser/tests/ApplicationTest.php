<?php

use PHPUnit\Framework\TestCase;
use Federaliser\Application;
use Federaliser\Router;
use Federaliser\Helpers;

class ApplicationTest extends TestCase
{
    private $routerMock;
    private $application;

    protected function setUp(): void
    {
        $this->routerMock = $this->createMock(Router::class);
        $this->application = new Application('/path/to/config.ini');
    }

    public function testInitializationWithValidConfig()
    {
        $this->assertInstanceOf(Application::class, $this->application);
    }

    public function testThrowsExceptionOnInvalidConfig()
    {
        $this->expectException(InvalidArgumentException::class);
        new Application('/invalid/path/to/config.ini');
    }

    public function testConfigurationParsing()
    {
        $this->application->parseConfig();
        $configData = $this->application->getConfigData();

        $this->assertIsArray($configData);
        $this->assertArrayHasKey('settings', $configData);
    }

    public function testRoutingAndRequestHandling()
    {
        $this->routerMock->expects($this->once())
                         ->method('dispatch')
                         ->willReturn('Handled Request');

        $this->application->setRouter($this->routerMock);
        $result = $this->application->handleRequest();

        $this->assertEquals('Handled Request', $result);
    }
}
