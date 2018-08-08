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

    public function saveDataToQueue(string $queueName, string $data): int
    {
        return $this->redisConnection->lpush($queueName, [$data]);
    }

    public function countQueue(string $queueName): int
    {
        return $this->redisConnection->llen($queueName);
    }

    public function getDataFromQueue(string $queueName): string
    {
        return $this->redisConnection->lpop($queueName);
    }

    private function getConnection()
    {
        //create connection with redis
        $this->redisConnection = new Client([
            'scheme' => $this->redisConfiguration->getScheme(),
            'host'   => $this->redisConfiguration->getHost(),
            'port'   => $this->redisConfiguration->getPort(),
        ]);

        return $this->redisConnection;
    }

    public function getServiceIdentifier(): string
    {
        return self::class;
    }

    public function getSetsMember(string $setName, string $member): int
    {
        return $this->redisConnection->sismember($setName, $member);
    }

    public function addSetsMember(string $setName, string $member): int
    {
        return $this->redisConnection->sadd($setName, [$member]);
    }

    public function removeSetsMember(string $setName, string $member): int
    {
        return $this->redisConnection->srem($setName, $member);
    }
}