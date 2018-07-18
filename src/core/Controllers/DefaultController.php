<?php
namespace Akmb\Core\Controllers;

use Akmb\Core\Request;

class DefaultController
{
    /**
     * @var Request|null $request
     */
    protected $request = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->render(get_class($this));
    }

    public function render($msg)
    {
        $this->setDefaultHeaders();
        return json_encode([
            'status' => 'success',
            'data' => $msg
        ]);
    }

    public function renderError(string $msg)
    {
        $this->setDefaultHeaders();
        return json_encode([
            'status' => 'error',
            'message' => $msg
        ]);
    }

    private function setDefaultHeaders()
    {
        header('Content-Type: application/json');
    }
}
