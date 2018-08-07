<?php
namespace Akmb\Core\ServiceContainer;

use Akmb\Core\Services\Logger\Logger;
use Akmb\Core\Services\Redis\Redis;
use Akmb\Core\Services\Redis\RedisConfiguration;

class ServiceFactory
{
    public static function createService(array $config): ServiceContainer
    {
        $serviceContainer = new ServiceContainer();
        $serviceContainer->addService(self::getLogger());
        $serviceContainer->addService(self::getRedis($config['redis']));

        return $serviceContainer;
    }

    private static function getLogger(): Logger
    {
        return new Logger();
    }

    private static function getRedis(array $config): Redis
    {
        return new Redis(new RedisConfiguration(
            $config['scheme'],
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        ));
    }
}