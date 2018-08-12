<?php
namespace Akmb\Test\Core;

use PHPUnit\Framework\TestCase;
use Akmb\Core\Request;

class RequestTest extends TestCase
{
    /**
     * @var array $server
     */
    private $server = [
        'REQUEST_URI' => '/test/something'
    ];

    /**
     * @var array $post
     */
    private $post = [
        'post' => 'data'
    ];

    /**
     * @var array $get
     */
    private $get = [
        'get' => 'data'
    ];

    /**
     * @var Request $request
     */
    private $request = null;

    public function setup()
    {
        $this->request = new Request(
            $this->server,
            $this->post,
            $this->get
        );
    }

    public function testGetUri()
    {
        $this->assertEquals(
            $this->server['REQUEST_URI'],
            $this->request->getUri()
        );
    }

    public function testGetServer()
    {
        $this->assertEquals(
            $this->server,
            $this->request->getServer()
        );
    }

    public function testGetPost()
    {
        $this->assertEquals(
            $this->post,
            $this->request->getPost()
        );
    }

    public function testGetGet()
    {
        $this->assertEquals(
            $this->get,
            $this->request->getGet()
        );
    }
}
