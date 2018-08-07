<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config/config.php";

use Akmb\Core\Dispatcher;
use Akmb\Core\Request;
use Akmb\Core\Router;
use Akmb\Core\ServiceContainer\ServiceFactory;

try {
    $request = new Request($_SERVER, $_POST, $_GET);
    $router = new Router($request);

    $serviceContainer = ServiceFactory::createService($config);

    $dispatcher = new Dispatcher(
        $router,
        $request,
        $serviceContainer
    );

    print $dispatcher->dispatch();
} catch (\Throwable $t) {
    print sprintf(
        'Total disaster, but it\'s not my fault.  -> [%s]',
        $t->getMessage()
    );
}
