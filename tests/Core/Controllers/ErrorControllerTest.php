<?php
namespace Akmb\Test\Core\Controllers;

use Akmb\Core\Controllers\ErrorController;
use Akmb\Test\BaseTest;

class ErrorControllerTest extends BaseTest
{
    /**
     * @var ErrorController|null $controller
     */
    private $controller = null;

    public function setUp()
    {
        $this->controller = new ErrorController(
            $this->mockRequest(),
            $this->mockServiceContainer()
        );
    }

    public function testInternalError()
    {
        $msg = 'internal error';
        $this->assertEquals(
            '{"status":"error","message":"internal error"}',
            $this->controller->internalError($msg)
        );
    }

    public function testNotFound()
    {
        $msg = 'not found';
        $this->assertEquals(
            '{"status":"error","message":"not found"}',
            $this->controller->notFound($msg)
        );
    }

    public function testBadRequest()
    {
        $msg = 'bad request';
        $this->assertEquals(
            '{"status":"error","message":"bad request"}',
            $this->controller->notFound($msg)
        );
    }

    public function testMethodIsNotAllowed()
    {
        $msg = 'not allowed';
        $this->assertEquals(
            '{"status":"error","message":"not allowed"}',
            $this->controller->methodIsNotAllowed($msg)
        );
    }
}
