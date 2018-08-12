<?php
namespace Akmb\Core\Services\Redis;

use Akmb\Core\Services\Redis\Interfaces\RedisConfigurationInterface;
use Akmb\Core\ServiceContainer\Interfaces\ServiceInterface;
use Predis\Client;

class Redis implements ServiceInterface
{
    /**
     * @var RedisConfigurationInterface
     */
    private $redisConfiguration = null;

    /**
     * @var null
     */
    private $redisConnection = null;

    /**
     * Redis constructor.
     * @param RedisConfigurationInterface $redisConfiguration
     */
    public function __construct(RedisConfigurationInterface $redisConfiguration)
    {
        $this->redisConfiguration = $redisConfiguration;

        $this->redisConnection = $this->getConnection();
    }

    public function getServiceIdentifier(): string
    {
        return self::class;
    }

    public function getConnection()
    {
        if (is_null($this->redisConnection)) {
            //create connection with redis
            $this->redisConnection = new Client([
                'scheme' => $this->redisConfiguration->getScheme(),
                'host' => $this->redisConfiguration->getHost(),
                'port' => $this->redisConfiguration->getPort(),
            ]);
        }

        return $this->redisConnection;
    }

    public function saveDataToQueue(string $queueName, string $data): int
    {
        return $this->getConnection()->lpush($queueName, [$data]);
    }

    public function countQueue(string $queueName): int
    {
        return $this->getConnection()->llen($queueName);
    }

    public function getDataFromQueue(string $queueName): ?string
    {
        return $this->getConnection()->lpop($queueName);
    }

    public function getSetsMember(string $setName, string $member): int
    {
        return $this->getConnection()->sismember($setName, $member);
    }

    public function addSetsMember(string $setName, string $member): int
    {
        return $this->getConnection()->sadd($setName, [$member]);
    }

    public function removeSetsMember(string $setName, string $member): int
    {
        return $this->getConnection()->srem($setName, $member);
    }
}