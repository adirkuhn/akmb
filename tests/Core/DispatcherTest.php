<?php
namespace Akmb\Test\Core;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Dispatcher;
use Akmb\Core\Exceptions\ActionNotFoundException;
use Akmb\Core\Request;
use Akmb\Core\Router;
use Akmb\Core\ServiceContainer\ServiceContainer;

class DispatcherTest extends BaseTest
{
    /**
     * @var Dispatcher|null $dispatcher
     */
    protected $dispatcher = null;

    /**
     * @var Router|null $router
     */
    protected $router = null;

    /**
     * @var Request|null $request
     */
    protected $request = null;

    /**
     * @var ServiceContainer|null $serviceContainer
     */
    protected $serviceContainer;

    /**
     * test calling invalid controller
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    public function testDispatchToInvalidController()
    {
        $this->dispatcher = $this->getDispatcher([
            'REQUEST_URI' => '/unknown/nonono'
        ]);

        $response = json_decode($this->dispatcher->dispatch(), true);

        $this->assertEquals(
            'error',
            $response['status']
        );

        $this->assertContains(
            '404',
            $response['message']
        );
    }

    /**
     * Should throw an exception when calling an invalid action
     * @throws ActionNotFoundException
     * @throws \Akmb\Core\Exceptions\ControllerNotFoundException
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    public function testExceptionInvalidAction()
    {
        $this->dispatcher = $this->getDispatcher(
            ['REQUEST_URI' => '/main/nonono']
        );

        $this->expectException(ActionNotFoundException::class);
        $res = $this->dispatcher->callAction();

        var_dump($res);
    }

    /**
     * Should call the correct Controller / Action
     * @throws \Akmb\Core\Exceptions\ControllerNotFoundException
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     * @throws ActionNotFoundException
     */
    public function testCallAction()
    {
        $this->dispatcher = $this->getDispatcher(['REQUEST_URI' => '/main/index']);

        $response = json_decode($this->dispatcher->callAction(), true);

        $this->assertEquals(
            'success',
            $response['status']
        );
    }

    /**
     * @throws ActionNotFoundException
     * @throws \Akmb\Core\Exceptions\ControllerNotFoundException
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    public function testCallActionWithNotAllowedHttpMethod()
    {
        $controller = $this->getMockBuilder(DefaultController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $controller->expects($this->once())
            ->method('isAllowedRequestMethod')
            ->willReturn(false);

        $this->getDispatcher(['REQUEST_URI' => '/main/index']);
        $this->dispatcher = $this->getMockBuilder(Dispatcher::class)
            ->setConstructorArgs([$this->router, $this->request, $this->serviceContainer])
            ->getMock();

        $this->dispatcher->expects($this->once())
            ->method('getControllerInstance')
            ->willReturn($controller);

        $response = $this->dispatcher->callAction();

        var_dump($response);
    }

    /**
     * @param array $request
     * @return Dispatcher
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    protected function getDispatcher(array $request): Dispatcher
    {
        $this->request = $this->mockRequest($request);

        $this->router = $this->mockRouter($this->request);

        $this->serviceContainer = $this->mockServiceContainer();

        $this->dispatcher = new Dispatcher(
            $this->router,
            $this->request,
            $this->serviceContainer
        );

        return $this->dispatcher;
    }
}
