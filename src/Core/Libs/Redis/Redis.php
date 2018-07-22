<?php
namespace Akmb\Core\Libs\Redis;

use Akmb\Core\Libs\Redis\Interfaces\RedisConfigurationInterface;

class Redis
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

    public function saveData(string $id, array $data): array
    {
        //save to redis
        return $data;
    }

    public function getData(string $id): array
    {
        // get from redis
        return [];
    }

    private function getConnection()
    {
        //create connection with redis
        $this->redisConfiguration->getHost();
        return $this->redisConnection;
    }
}