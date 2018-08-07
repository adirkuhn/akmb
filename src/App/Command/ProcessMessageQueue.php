<?php
namespace Akmb\App\Command;

use Akmb\App\Constants\Queue;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\Services\Redis\Redis;

class ProcessMessageQueue
{
    /**
     * @var ServiceContainer $serviceContainer
     */
    private $serviceContainer;

    /**
     * @var Redis|null $redis
     */
    private $redis = null;

    /**
     * ProcessMessageQueue constructor.
     * @param ServiceContainer $serviceContainer
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->redis = $serviceContainer->getService(Redis::class);
    }

    public function hasMessagesInQueue()
    {
        return $this->redis->countQueue(Queue::SmsListMessageName) > 0;
    }

    public function sendMessage()
    {
        $queuedMessage = $this->redis->getDataFromQueue(Queue::SmsListMessageName);

    }
}