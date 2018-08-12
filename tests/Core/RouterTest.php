<?php
namespace Akmb\Test\Core;

use Akmb\App\Controllers\MainController;
use Akmb\Core\Exceptions\ControllerNotFoundException;
use Akmb\Core\Router;
use Akmb\Test\BaseTest;

class RouterTest extends BaseTest
{
    /**
     * @var Router|null $router
     */
    private $router = null;

    public function setup()
    {
        $this->router = new Router(
            $this->mockRequest()
        );
    }

    /**
     * Should throw an exception with empty data for $_SERVER params
     * REQUEST_URI
     * @throws ControllerNotFoundException
     */
    public function testExceptionOnGetController()
    {
        $this->expectException(ControllerNotFoundException::class);
        $this->router->getController();
    }

    /**
     * With default server URI should return the MainController:index
     * @throws ControllerNotFoundException
     */
    public function testParseGetMainController()
    {
        $this->router = new Router(
            $this->mockRequest(['REQUEST_URI' => '/'])
        );

        $this->assertEquals(
            MainController::class,
            $this->router->getController()
        );

        $this->assertEquals(
            'index',
            $this->router->getAction()
        );
    }
}
