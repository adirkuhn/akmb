<?php
namespace Akmb\Core;

class Request
{
    const POST = 'POST';

    const GET = 'GET';

    /**
     * @var array $server
     */
    private $server;

    /**
     * @var array $post
     */
    private $post;

    /**
     * @var array $get
     */
    private $get;

    /**
     * Request constructor.
     * @param array $server
     * @param array $post
     * @param array $get
     */
    public function __construct(array $server, array $post, array $get)
    {
        $this->server = $server;
        $this->post = $post;
        $this->get = $get;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }
}
