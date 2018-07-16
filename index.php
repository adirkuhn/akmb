<?php
require_once __DIR__ . "/vendor/autoload.php";
use Akmb\Core\Dispatcher;

try {
    $request = new \Akmb\Core\Request($_SERVER, $_POST, $_GET);
    $router = new \Akmb\Core\Router($request);

    $dispatcher = new Dispatcher(
        $router,
        $request
    );
    print $dispatcher->dispatch();
} catch (\Throwable $t) {
    print sprintf(
        'Total disaster, but it\'s not my fault.  -> [%s]',
        $t->getMessage()
    );
}
