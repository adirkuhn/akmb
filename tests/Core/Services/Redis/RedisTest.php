<?php
namespace Akmb\Test\Core\Services\Redis;

use Akmb\Core\Services\Redis\Redis;
use Akmb\Core\Services\Redis\RedisConfiguration;
use Akmb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class RedisTest extends BaseTest
{
    /**
     * @var Redis|MockObject|null
     */
    private $redis = null;

    private $config = [
        'scheme' => 'tcp',
        'host' => 'localhost',
        'port' => '6666',
        'user' => 'username',
        'password' => 'password'
    ];

    /**
     *
     */
    public function setUp()
    {
        $this->redis = $this->getMockBuilder(Redis::class)
            ->setConstructorArgs([new RedisConfiguration(
                $this->config['scheme'],
                $this->config['host'],
                $this->config['port'],
                $this->config['user'],
                $this->config['password']
            )])
            ->setMethods([
                'getConnection'
            ])
            ->getMock();

        $this->redis->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->getRedisConnectionMock());
    }

    public function testSaveDataToQueue()
    {
        $this->assertEquals(
            1,
            $this->redis->saveDataToQueue('test', 'test')
        );
    }

    public function testCountQueue()
    {
        $this->assertEquals(
            1,
            $this->redis->countQueue('test')
        );
    }

    public function testGetServiceIdentifier()
    {
        $this->assertEquals(
            Redis::class,
            $this->redis->getServiceIdentifier()
        );
    }

    public function testGetDataFromQueue()
    {
        $queueName = 'test';

        $this->assertEquals(
            $queueName,
            $this->redis->getDataFromQueue($queueName)
        );
    }

    public function testGetSetsMember()
    {
        $set = 'test';
        $this->assertEquals(
            1,
            $this->redis->getSetsMember($set, $set)
        );
    }

    public function testAddSetsMember()
    {
        $set = 'test';
        $this->assertEquals(
            1,
            $this->redis->addSetsMember($set, $set)
        );
    }

    public function testRemoveSetsMember()
    {
        $set = 'test';
        $this->assertEquals(
            1,
            $this->redis->removeSetsMember($set, $set)
        );
    }

    private function getRedisConnectionMock()
    {
        return new class {
            public function lpush(string $queueName, array $data): int
            {
                return 1;
            }

            public function llen(string $queue): int
            {
                return 1;
            }

            public function lpop(string $queueName): string
            {
                return $queueName;
            }

            public function sismember(string $setName, string $member): int
            {
                return 1;
            }

            public function sadd(string $setName, array $member): int
            {
                return count($member);
            }

            public function srem(string $setName, string $member): int
            {
                return 1;
            }
        };
    }
}