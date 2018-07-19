<?php
namespace Akmb\Core;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Controllers\ErrorController;
use Akmb\Core\Exceptions\ActionNotFoundException;
use Akmb\Core\Exceptions\ControllerNotFoundException;

class Router
{
    const VALIDATE = 'validate';

    const GET_ERRORS = 'getErrors';

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

    /**
     * @return mixed
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     */
    public function callAction()
    {
        $controller = $this->getController();

        if ($controller->isAllowedRequestMethod($this->request->getRequestMethod()) === false)
        {
            return (new ErrorController($this->request))->methodIsNotAllowed(sprintf(
               'Request method [%s] is not allowed.',
               $this->request->getRequestMethod()
            ));
        }

        //check if there is validation
        if (method_exists($controller, self::VALIDATE)) {
            if (call_user_func([$controller, self::VALIDATE], $this->request) === false) {
                return (new ErrorController($this->request))->badRequest(json_encode(
                    call_user_func([$controller, self::GET_ERRORS])
                ));
            }
        }

        //call the controller
        if (method_exists($controller, $this->action)) {
            return call_user_func([$controller, $this->action], $this->request);
        }

        throw new ActionNotFoundException(get_class($controller));
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
