<?php
namespace Akmb\Test\App\Controllers;

use Akmb\App\Controllers\SmsController;
use Akmb\App\Message\MessageQueue;
use Akmb\Core\Request;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\Services\Redis\Redis;
use Akmb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class SmsControllerTest extends BaseTest
{
    /**
     * @var SmsController|null $controller
     */
    private $controller = null;

    private $mockService = null;

    public function setUp()
    {
        $redisMock = $this->getMockBuilder(Redis::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceContainer|MockObject $mockService */
        $this->mockService = $this->getMockBuilder(ServiceContainer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getService'])
            ->getMock();
        $this->mockService->expects($this->any())
            ->method('getService')
            ->with(Redis::class)
            ->willReturn($redisMock);

        $this->controller = new SmsController(
            $this->mockRequest(),
            $this->mockService
        );
    }

    public function testValidate()
    {
        $invalidRequest = new Request([], [], []);
        $this->assertFalse(
            $this->controller->validate($invalidRequest)
        );

        $validRequestEmptyKeys = new Request(
            [],
            ['destination' => '', 'message' => 'msg'],
            []
        );
        $this->assertFalse(
            $this->controller->validate($validRequestEmptyKeys)
        );

        $emptyMsisdn = new Request(
            [],
            ['destination' => '1', 'message' => 'msg'],
            []
        );
        $this->assertFalse($this->controller->validate($emptyMsisdn));

        $validRequest = new Request(
            [],
            ['destination' => '1111111111', 'message' => 'msg'],
            []
        );
        $this->assertTrue($this->controller->validate($validRequest));
    }

    public function testSend()
    {
        $data = [
            'destination' => '1111111111',
            'message' => 'hello this is a test.'
        ];

        $result = $this->controller->send(new Request([],$data, []));

        $this->assertEquals(
            '{"status":"success","data":"Message sent."}',
            $result
        );
    }

    public function testSendException()
    {
        $result = $this->controller->send(new Request([], [], []));

        $this->assertContains(
            'Unable to send SMS.',
            $result
        );
    }

    public function testSendDuplicatedMessage()
    {
        /** @var SmsController|MockObject $controller */
        $controller = $this->getMockBuilder(SmsController::class)
            ->setConstructorArgs([$this->mockRequest(), $this->mockService])
            ->setMethods(['getMessageQueue'])
            ->getMock();

        $messageQueue = $this->getMockBuilder(MessageQueue::class)
            ->disableOriginalConstructor()
            ->setMethods(['isDuplicated'])
            ->getMock();
        $messageQueue->expects($this->any())
            ->method('isDuplicated')
            ->willReturn(true);

        $controller->expects($this->any())
            ->method('getMessageQueue')
            ->willReturn($messageQueue);

        $data = [
            'destination' => '1111111111',
            'message' => 'hello this is a test.'
        ];

        $result = $controller->send(new Request([], $data, []));

        $this->assertContains(
            'Duplicated message',
            $result
        );
    }
}
