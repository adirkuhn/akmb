<?php
namespace Akmb\Test\Core;

use Akmb\Core\Dispatcher;
use Akmb\Core\Request;
use Akmb\Core\Router;

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
     * test calling invalid controller
     */
    public function testDispatchToInvalidController()
    {
        $this->request = $this->mockRequest([
            'REQUEST_URI' => '/invalid/invalid'
        ]);

        $this->router = $this->mockRouter($this->request);

        $this->dispatcher = new Dispatcher(
            $this->router,
            $this->request
        );

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
}
