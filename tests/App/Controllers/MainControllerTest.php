<?php
namespace Akmb\Test\App\Controllers;

use Akmb\App\Controllers\MainController;
use Akmb\Test\BaseTest;

class MainControllerTest extends BaseTest
{
    /**
     * @var MainController|null $controller
     */
    private $controller = null;

    public function setUp()
    {
        $this->controller = new MainController(
            $this->mockRequest(),
            $this->mockServiceContainer()
        );
    }

    public function testIndex()
    {
        $this->assertEquals(
            '{"status":"success","data":"Main:index"}',
            $this->controller->index()
        );
    }
}
