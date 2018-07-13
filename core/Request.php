<?php
namespace Akmb\Core;

class Request
{
    /**
     * @var string $server
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

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    public function getServer(): array
    {
        return $this->server;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function getGet(): array
    {
        return $this->get;
    }
}
