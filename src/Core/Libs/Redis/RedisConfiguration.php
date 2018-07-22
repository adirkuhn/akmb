<?php
namespace Akmb\Core\Libs\Redis;

use Akmb\Core\Libs\Redis\Interfaces\RedisConfigurationInterface;

class RedisConfiguration implements RedisConfigurationInterface
{
    /**
     * @var string $host
     */
    private $host;

    /**
     * @var string $port
     */
    private $port;

    /**
     * @var string $user
     */
    private $user;

    /**
     * @var string $password
     */
    private $password;

    /**
     * RedisConfiguration constructor.
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $port, string $user, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}