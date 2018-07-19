<?php
namespace Akmb\Core\Controllers;

use Akmb\Core\Request;

class DefaultController
{
    /**
     * @var Request|null $request
     */
    protected $request = null;

    /**
     * @var bool $allowGet
     */
    protected $allowGet = true;

    /**
     * @var bool $allowPost
     */
    protected $allowPost = true;

    /**
     * DefaultController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param bool $allowed
     * @return bool
     */
    public function setAllowGet(bool $allowed): bool
    {
        $this->allowGet = $allowed;
        return $this->allowGet;
    }

    /**
     * @param bool $allowed
     * @return bool
     */
    public function setAllowPost(bool $allowed): bool
    {
        $this->allowPost = $allowed;
        return $this->allowPost;
    }

    public function isAllowedRequestMethod(string $method) {
        switch ($method) {
            case Request::POST:
                return $this->isPostAllowed();
            case Request::GET:
                return $this->isGetAllowed();
            default:
                return false;
        }
    }

    /**
     * @return bool
     */
    public function isGetAllowed(): bool
    {
        return $this->allowGet;
    }

    /**
     * @return bool
     */
    public function isPostAllowed(): bool
    {
        return $this->allowPost;
    }

    /**
     * index
     */
    public function index()
    {
        $this->render(get_class($this));
    }

    /**
     * @param $msg
     * @return string
     */
    public function render($msg): string
    {
        $this->setDefaultHeaders();
        return json_encode([
            'status' => 'success',
            'data' => $msg
        ]);
    }

    /**
     * @param string $msg
     * @return string
     */
    public function renderError(string $msg): string
    {
        $this->setDefaultHeaders();
        return json_encode([
            'status' => 'error',
            'message' => $msg
        ]);
    }

    /**
     * set default headers
     */
    private function setDefaultHeaders(): void
    {
        header('Content-Type: application/json');
    }

    /**
     * @param string $msg
     * @param int $httpStatusCode
     */
    protected function setHeaders(string $msg, int $httpStatusCode): void
    {
        $server = $this->request->getServer();
        $serverProtocol = $server['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
        header($serverProtocol . ' ' . $msg, true, $httpStatusCode);
    }
}
