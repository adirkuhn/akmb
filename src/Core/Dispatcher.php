<?php
namespace Akmb\Core;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Controllers\ErrorController;
use Akmb\Core\Exceptions\ActionNotFoundException;
use Akmb\Core\Exceptions\ControllerNotFoundException;
use Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\Services\Logger\Logger;

class Dispatcher
{
    /**
     * @var Router $router;
     */
    private $router = null;

    /**
     * @var Request $request;
     */
    private $request = null;

    /**
     * @var ServiceContainer|null
     */
    private $serviceContainer = null;

    /**
     * @var Logger|null $logger
     */
    private $logger = null;

    /**
     * Dispatcher constructor.
     * @param Router $router
     * @param Request $request
     * @param ServiceContainer $serviceContainer
     * @throws ServiceNotFoundException
     */
    public function __construct(Router $router, Request $request, ServiceContainer $serviceContainer)
    {
        $this->router = $router;
        $this->request = $request;
        $this->serviceContainer = $serviceContainer;
        $this->logger = $this->serviceContainer->getService(Logger::class);
    }

    public function dispatch()
    {
        try {
            return $this->callAction();
        } catch (ControllerNotFoundException | ActionNotFoundException $e) {
            $msg = '404 we can\'t find what you are looking for.';
            $this->logger->error(sprintf(
                '%s: [%s] - [%s]',
                $msg,
                $e->getMessage(),
                $e->getTraceAsString()
            ));

            return (new ErrorController($this->request, $this->serviceContainer))->notFound($msg);
        } catch (\Throwable $e) {
            $msg = 'Unhandled error happened';
            $this->logger->error(sprintf(
                '%s: [%s]',
                $msg,
                $e->getMessage()
            ));

            return (new ErrorController($this->request, $this->serviceContainer))->internalError($msg);
        }
    }

    /**
     * @return mixed
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     */
    public function callAction()
    {
        /** @var DefaultController $controller */
        $controller = $this->getControllerInstance(
            $this->router->getController()
        );

        if ($controller->isAllowedRequestMethod($this->request->getRequestMethod()) === false)
        {
            return (new ErrorController($this->request, $this->serviceContainer))->methodIsNotAllowed(sprintf(
                'Request method [%s] is not allowed.',
                $this->request->getRequestMethod()
            ));
        }

        //check if there is validation
        if ($controller->validate($this->request) === false) {
            return (new ErrorController($this->request, $this->serviceContainer))->badRequest(json_encode(
                $controller->getErrors()
            ));
        }

        //call the controller
        if (method_exists($controller, $this->router->getAction())) {
            return call_user_func([$controller, $this->router->getAction()], $this->request);
        }

        throw new ActionNotFoundException(get_class($controller));
    }

    /**
     * @param string $controllerName
     * @return DefaultController
     */
    public function getControllerInstance(string $controllerName): DefaultController
    {
        return new $controllerName($this->request, $this->serviceContainer);
    }
}
