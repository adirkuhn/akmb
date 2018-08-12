<?php
namespace Akmb\App\Message;

use Akmb\App\Entities\Message;
use Akmb\Core\Services\Redis\Redis;

class MessageQueue
{
    const QUEUE_NAME = 'SmsQueueList';
    const QUEUE_MEMBER = 'SmsQueueMember';

    /**
     * @var Redis|null
     */
    private $redis = null;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function isDuplicated(Message $message): bool
    {
        return (bool) $this->redis->getSetsMember(self::QUEUE_MEMBER, $message->hash());
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function queueMessage(Message $message): bool
    {
        if ($this->isDuplicated($message)) {
            return false;
        }

        $this->redis->addSetsMember(self::QUEUE_MEMBER, $message->hash());
        $this->redis->saveDataToQueue(self::QUEUE_NAME, $message->serialize());

        return true;
    }

    public function popMessageFromQueue(): ?Message
    {
        $data = $this->redis->getDataFromQueue(self::QUEUE_NAME);

        if (is_null($data)) {
            return null;
        }

        /** @var Message $message */
        $message = unserialize($data);
        $this->redis->removeSetsMember(self::QUEUE_MEMBER, $message->hash());

        return $message;
    }

    public function hasMessagesInQueue(): bool
    {
        return $this->redis->countQueue(self::QUEUE_NAME) > 0;
    }
}