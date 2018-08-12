<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Akmb\App\Message\MessageQueue;
use Akmb\Core\ServiceContainer\ServiceFactory;
use Akmb\Core\Services\Redis\Redis;
use Akmb\Core\Services\Logger\Logger;

try {
    if (empty($config['messageBird']) || empty($config['messageBird']['token'])) {
        throw new \Exception('No configuration for messageBird API has been found.');
    }

    $services = ServiceFactory::createService($config);
    $messageQueue = new MessageQueue($services->getService(Redis::class));

    /** @var Logger $logger */
    $logger = $services->getService(Logger::class);

    while ($messageQueue->hasMessagesInQueue()) {

        $message = $messageQueue->popMessageFromQueue();

        $parts = $message->prepareMessage();

        //sending the message
        try {
            $mbClient = new \MessageBird\Client($config['messageBird']['token']);

            foreach ($parts as $k => $part) {
                $logger->info(sprintf(
                    'Sending SMS with UDH [%s] - DATA [%s]',
                    $part['udh'],
                    $part['message']
                ));

                $mbMessage = new \MessageBird\Objects\Message();
                $mbMessage->originator = 'Adir Kuhn';
                $mbMessage->recipients = [$message->getDestination()];
                $mbMessage->setBinarySms($part['udh'], $part['message']);

                $mbClient->messages->create($mbMessage);

                sleep(1);
            }
        } catch (\Exception $e) {
            $logger->error(sprintf(
                'Unable to send the message due an error [%s].',
                $e->getMessage()
            ));

            $logger->info('Putting the message in the queue again');

            //$messageQueue->queueMessage($message);
        }

        sleep(1);
    }

    echo PHP_EOL;
    echo "No more messages in the queue.";
    echo PHP_EOL;
} catch (\Exception $e) {
    echo sprintf('Unable to process message queue. Error: [%s]', $e->getMessage());
    echo PHP_EOL;
}