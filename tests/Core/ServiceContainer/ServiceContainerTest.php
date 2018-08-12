<?php
namespace Akmb\Test\Core\ServiceContainer;

use Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException;
use Akmb\Core\ServiceContainer\Interfaces\ServiceInterface;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\ServiceContainer\ServiceFactory;
use Akmb\Core\Services\Redis\Redis;
use Akmb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class ServiceContainerTest extends BaseTest
{
    /**
     * @var ServiceContainer|null $service
     */
    private $service = null;

    public function setUp()
    {
        $config = ['redis' => [
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => '6379',
            'user' => '',
            'password' => ''
        ]];

        $this->service =  ServiceFactory::createService($config);
    }

    public function testHasService()
    {
        $this->assertTrue(
            $this->service->has(Redis::class)
        );
    }

    public function testDoNotHasService()
    {
        $this->assertFalse(
            $this->service->has('InvalidService')
        );
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function testGetService()
    {
        $this->assertInstanceOf(
            Redis::class,
            $this->service->getService(Redis::class)
        );
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function testGetInvalidService()
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->service->getService('InvalidService');
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function testAddService()
    {
        /** @var ServiceInterface|MockObject $mockedService */
        $mockedService = $this->getMockBuilder(ServiceInterface::class)
            ->setMethods(['getServiceIdentifier'])
            ->getMock();

        $mockedService->expects($this->any())
            ->method('getServiceIdentifier')
            ->willReturn('testService');

        $this->service->addService($mockedService);

        $this->assertInstanceOf(
            get_class($mockedService),
            $this->service->getService($mockedService->getServiceIdentifier())
        );
    }
}
