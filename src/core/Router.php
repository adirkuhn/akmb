<?php
namespace Akmb\Core;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Exceptions\ActionNotFoundException;
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
            return new $controller($this->request);
        }

        throw new ControllerNotFoundException();
    }

    public function callAction()
    {
        $controller = $this->getController();

        if (method_exists($controller, $this->action)) {
            return call_user_func([$controller, $this->action], $this->params);
        }

        throw new ActionNotFoundException(get_class($controller));
    }

    private function getControllerWithNameSpace()
    {
        return sprintf(
            '%s\\App\\Controllers\\%sController',
            strtok(__NAMESPACE__, '\\'),
            $this->controller
        );
    }

    private function parseRequest(): void
    {
        if ($this->request->getUri() === '/') {
            $controller = 'Main';
            $action = 'index';
            $params = [];
        } else {
            $data = explode('/', $this->request->getUri());

            $controller = $data[1] ?? '';
            $action = $data[2] ?? '';
            $params = isset($data[3]) ? array_slice($data, 3) : [];
        }

        $this->controller = ucfirst($controller);
        $this->action = $action;
        $this->params = $params;
    }
}
