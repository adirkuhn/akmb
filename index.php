<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config/config.php";

use Akmb\Core\Dispatcher;
use Akmb\Core\Extra\Logger;
use Akmb\Core\Request;
use Akmb\Core\Router;
use Akmb\Core\Libs\Redis\RedisConfiguration;
use Akmb\Core\Libs\Redis\Redis;

try {
    $request = new Request($_SERVER, $_POST, $_GET);
    $router = new Router($request);
    $logger = new Logger();

    $redis = new Redis(new RedisConfiguration(
        $config['redis']['host'],
        $config['redis']['port'],
        $config['redis']['user'],
        $config['redis']['password']
    ));

    $dispatcher = new Dispatcher(
        $router,
        $request,
        $logger,
        $redis
    );

    print $dispatcher->dispatch();
} catch (\Throwable $t) {
    print sprintf(
        'Total disaster, but it\'s not my fault.  -> [%s]',
        $t->getMessage()
    );
}
