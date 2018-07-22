<?php
namespace Akmb\Core;

use Akmb\Core\Controllers\ErrorController;
use Akmb\Core\Exceptions\ActionNotFoundException;
use Akmb\Core\Exceptions\ControllerNotFoundException;
use Akmb\Core\Extra\Logger;
use Akmb\Core\Libs\Redis\Redis;

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
     * @var Logger|null $logger
     */
    private $logger = null;

    /**
     * @var Redis|null $redis
     */
    private $redis = null;

    /**
     * Dispatcher constructor.
     * @param Router $router
     * @param Request $request
     * @param Logger|null $logger
     * @param Redis $redis
     */
    public function __construct(Router $router, Request $request, Logger $logger, Redis $redis)
    {
        $this->router = $router;
        $this->request = $request;
        $this->logger = $logger;
        $this->redis = $redis;
    }

    public function dispatch()
    {
        try {
            return $this->router->callAction();
        } catch (ControllerNotFoundException | ActionNotFoundException $e) {
            $msg = '404 we can\'t find what you are looking for.';
            $this->logger->error(sprintf(
                '%s: [%s] - [%s]',
                $msg,
                $e->getMessage(),
                $e->getTraceAsString()
            ));

            return (new ErrorController($this->request))->notFound($msg);
        } catch (\Throwable $e) {
            $msg = 'Unhandled error happened';
            $this->logger->error(sprintf(
                '%s: [%s]',
                $msg,
                $e->getMessage()
            ));

            return (new ErrorController($this->request))->internalError($msg);
        }
    }
}
