<?php
namespace Akmb\Test\Core\ServiceContainer;

use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\ServiceContainer\ServiceFactory;
use Akmb\Test\BaseTest;

class ServiceFactoryTest extends BaseTest
{
    public function testCreateService()
    {
        $config = ['redis' => [
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => '6379',
            'user' => '',
            'password' => ''
        ]];

        $service =  ServiceFactory::createService($config);

        $this->assertInstanceOf(ServiceContainer::class, $service);
    }
}