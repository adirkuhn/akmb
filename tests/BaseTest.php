<?php
namespace Akmb\Test;

use Akmb\Core\Request;
use Akmb\Core\Router;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\Services\Logger\Logger;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function testTest()
    {
        $this->assertTrue(true);
    }

    protected function mockRequest($server = [], $post = [], $get = []): Request
    {
        return new Request($server, $post, $get);
    }

    protected function mockRouter(Request $request): Router
    {
        return new Router($request);
    }

    protected function mockServiceContainer(): ServiceContainer
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->addService(new Logger());

        return $serviceContainer;
    }

    /**
     * @param $obj
     * @param $name
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    protected function callMethod($obj, $name, array $args = []) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}
