<?php
namespace Akmb\Test\Core\Services\Redis;

use Akmb\Core\Services\Redis\RedisConfiguration;
use Akmb\Test\BaseTest;

class RedisConfigurationTest extends BaseTest
{
    /**
     * @var RedisConfiguration|null $redisConfiguration
     */
    private $redisConfiguration = null;

    private $config = [
        'scheme' => 'tcp',
        'host' => 'localhost',
        'port' => '6666',
        'user' => 'username',
        'password' => 'password'
    ];

    public function setUp()
    {
        $this->redisConfiguration = new RedisConfiguration(
            $this->config['scheme'],
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password']
        );
    }

    public function testGetScheme()
    {
        $this->assertEquals(
            $this->config['scheme'],
            $this->redisConfiguration->getScheme()
        );
    }

    public function testGetHost()
    {
        $this->assertEquals(
            $this->config['host'],
            $this->redisConfiguration->getHost()
        );
    }

    public function testGetPort()
    {
        $this->assertEquals(
            $this->config['port'],
            $this->redisConfiguration->getPort()
        );
    }

    public function testGetUser()
    {
        $this->assertEquals(
            $this->config['user'],
            $this->redisConfiguration->getUser()
        );
    }

    public function testGetPassword()
    {
        $this->assertEquals(
            $this->config['password'],
            $this->redisConfiguration->getPassword()
        );
    }
}
