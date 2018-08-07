<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Akmb\App\Command\ProcessMessageQueue;
use Akmb\Core\ServiceContainer\ServiceFactory;

try {
    $processMessageQueue = new ProcessMessageQueue(ServiceFactory::createService($config));

    while ($processMessageQueue->hasMessagesInQueue()) {

        $processMessageQueue->sendMessage();

        sleep(1);
    }
} catch (\Exception $e) {
    echo sprintf('Unable to process message queue. Error: [%s]', $e->getMessage());
    echo PHP_EOL;
}