<?php
namespace Akmb\Test\Core\Controllers;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Request;
use Akmb\Test\BaseTest;

class DefaultControllerTest extends BaseTest
{
    /**
     * @var DefaultController|null $controller
     */
    private $controller = null;

    public function setUp()
    {
        $this->controller = new DefaultController(
            $this->mockRequest(),
            $this->mockServiceContainer()
        );
    }

    public function testSetAllowGet()
    {
        $this->controller->setAllowGet(true);
        $this->assertTrue($this->controller->isGetAllowed());
    }

    public function testSetAllowPost()
    {
        $this->controller->setAllowPost(true);
        $this->assertTrue($this->controller->isPostAllowed());
    }

    public function testIsAllowedRequestMethod() {
        $this->assertFalse($this->controller->isAllowedRequestMethod('UNKNOWN'));

        $this->controller->setAllowPost(true);
        $this->controller->setAllowGet(true);

        $this->assertTrue($this->controller->isAllowedRequestMethod(Request::POST));
        $this->assertTrue($this->controller->isAllowedRequestMethod(Request::GET));
    }

    public function testIndex()
    {
        $this->assertEquals(
            json_encode([
                'status' => 'success',
                'data' => get_class($this->controller)
            ]),
            $this->controller->index()
        );
    }

    public function testRender()
    {
        $this->assertEquals(
            '{"status":"success","data":""}',
            $this->controller->render('')
        );
    }

    public function renderError()
    {
        $msg = 'error';
        $error = json_encode([
            'status' => 'error',
            'message' => $msg
        ]);

        $this->assertEquals(
            $error,
            $this->controller->renderError($msg)
        );
    }
}
