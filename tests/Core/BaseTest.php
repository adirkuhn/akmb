<?php
namespace Akmb\Test\Core;

use Akmb\Core\Request;
use Akmb\Core\Router;
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
}
