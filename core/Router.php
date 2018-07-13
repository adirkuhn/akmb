<?php
namespace Akmb\Core;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Exceptions\ControllerNotFoundException;

class Router
{
    /**
     * @var Request|null $request
     */
    private $request = null;

    /**
     * @var string $controller
     */
    private $controller = null;

    /**
     * @var string $action
     */
    private $action = null;

    /**
     * @var string $params
     */
    private $params = null;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->parseRequest();
    }

    /**
     * @return DefaultController
     * @throws ControllerNotFoundException
     */
    public function getController(): DefaultController
    {
        $controller = $this->getControllerWithNameSpace();

        if (class_exists($controller)) {
            return new $controller;
        }

        throw new ControllerNotFoundException();
    }

    private function getControllerWithNameSpace()
    {
        return sprintf(
            '%s\\App\\%s',
            strtok(__NAMESPACE__, '\\'),
            $this->controller
        );
    }

    private function parseRequest(): void
    {
        if ($this->request->getUri() === '/') {
            $controller = 'MainController';
            $action = 'index';
            $params = '';
        } else {
            list(, $controller, $action, $params) = explode('/', $this->request->getUri());
        }

        $this->controller = ucfirst($controller);
        $this->action = $action;
        $this->params = $params;
    }
}
