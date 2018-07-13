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

    public function render($msg)
    {
        return $msg;
    }
}