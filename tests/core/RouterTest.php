<?php
namespace Akmb\Test\Core;

use Akmb\App\Controllers\MainController;
use Akmb\Core\Exceptions\ActionNotFoundException;
use Akmb\Core\Exceptions\ControllerNotFoundException;
use Akmb\Core\Router;

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
     */
    public function testExceptionOnGetController()
    {
        $this->expectException(ControllerNotFoundException::class);
        $this->router->getController();
    }

    /**
     * With default server URI should return the MainController:index
     */
    public function testParseGetMainController()
    {
        $this->router = new Router(
            $this->mockRequest(['REQUEST_URI' => '/'])
        );

        $this->assertInstanceOf(
            MainController::class,
            $this->router->getController()
        );
    }

    /**
     * Should throw an exception when calling an invalid action
     */
    public function testExceptionInvalidAction()
    {
        $this->router = new Router(
            $this->mockRequest(['REQUEST_URI' => '/main/nonono'])
        );

        $this->expectException(ActionNotFoundException::class);
        $this->router->callAction();
    }

    /**
     * assert that is calling the correct controller and action
     */
    public function testCallAction()
    {
        $this->router = new Router(
            $this->mockRequest(['REQUEST_URI' => '/main/index'])
        );

        $this->assertInstanceOf(
            MainController::class,
            $this->router->getController()
        );

        $response = json_decode($this->router->callAction(), true);

        $this->assertEquals(
            'success',
            $response['status']
        );
    }
}
