<?php
namespace Akmb\Core;

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
     * @return string
     * @throws ControllerNotFoundException
     */
    public function getController(): string
    {
        $controller = $this->getControllerWithNameSpace();

        if (class_exists($controller)) {
            return $controller;
        }

        throw new ControllerNotFoundException();
    }

    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
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
