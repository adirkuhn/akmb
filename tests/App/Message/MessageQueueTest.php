<?php
namespace Akmb\Test\App\Message;

use Akmb\App\Entities\Message;
use Akmb\App\Message\MessageQueue;
use Akmb\Core\Services\Redis\Redis;
use Akmb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class MessageQueueTest extends BaseTest
{
    /**
     * @var MessageQueue|null $messageQueue
     */
    private $messageQueue = null;

    /**
     * @var Redis|MockObject|null $redisMock
     */
    private $redisMock = null;

    public function setUp()
    {
        $this->redisMock = $this->getMockBuilder(Redis::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getSetsMember',
                'addSetsMember',
                'saveDataToQueue',
                'getDataFromQueue',
                'removeSetsMember',
                'countQueue'
            ])
            ->getMock();

        $this->messageQueue = new MessageQueue($this->redisMock);
    }

    public function testIsDuplicated()
    {
        $this->assertFalse(
            $this->messageQueue->isDuplicated(new Message())
        );

        $this->redisMock->expects($this->any())
            ->method('getSetsMember')
            ->willReturn(true);

        $this->assertTrue(
            $this->messageQueue->isDuplicated(new Message())
        );
    }

    public function testQueueMessageDuplicated()
    {
        $this->redisMock->expects($this->any())
            ->method('getSetsMember')
            ->willReturn(true);

        $this->assertFalse(
            $this->messageQueue->queueMessage(new Message())
        );
    }

    public function testQueueMessage()
    {
        $this->assertTrue(
            $this->messageQueue->queueMessage(new Message())
        );
    }

    public function testPopMessageFromQueueNoMessage()
    {
        $this->redisMock->expects($this->any())
            ->method('getDataFromQueue')
            ->willReturn(null);

        $this->assertNull(
            $this->messageQueue->popMessageFromQueue()
        );
    }

    public function testPopMessageFromQueue()
    {
        $message = new Message();

        $this->redisMock->expects($this->any())
            ->method('getDataFromQueue')
            ->willReturn($message->serialize());

        $this->assertInstanceOf(
            Message::class,
            $this->messageQueue->popMessageFromQueue()
        );
    }

    public function testHasNoMessagesInQueue()
    {
        $this->assertFalse(
            $this->messageQueue->hasMessagesInQueue()
        );
    }

    public function testHasMessagesInQueue()
    {
        $this->redisMock->expects($this->any())
            ->method('countQueue')
            ->willReturn(1);

        $this->assertTrue(
            $this->messageQueue->hasMessagesInQueue()
        );
    }
}