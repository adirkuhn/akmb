<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Akmb\App\Command\ProcessMessageQueue;
use Akmb\App\Message\MessageQueue;
use Akmb\Core\ServiceContainer\ServiceFactory;
use Akmb\Core\Services\Redis\Redis;

try {
    $services = ServiceFactory::createService($config);
    $processMessageQueue = new ProcessMessageQueue($services);
    $messageQueue = new MessageQueue($services->getService(Redis::class));

    while ($messageQueue->hasMessagesInQueue()) {

        $message = $messageQueue->popMessageFromQueue();

        echo $message->getMessage();

        sleep(1);
    }

    echo PHP_EOL;
    echo "No more messages in the queue.";
    echo PHP_EOL;
} catch (\Exception $e) {
    echo sprintf('Unable to process message queue. Error: [%s]', $e->getMessage());
    echo PHP_EOL;
}