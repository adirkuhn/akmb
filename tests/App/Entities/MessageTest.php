<?php
namespace Akmb\Test\App\Entities;

use Akmb\App\Entities\Message;
use Akmb\Test\BaseTest;

class MessageTest extends BaseTest
{
    /**
     * @var Message|null $message
     */
    private $message = null;

    /**
     * @var string $text
     */
    private $text = 'message text';

    /**
     * @var string $destination
     */
    private $destination = '1111111111';

    /**
     * @var string $unicodeText
     */
    private $unicodeText = '¡™£¢∞§§¶•ªªº––πøœ∑¥∂∫˙ßçç';

    public function setUp()
    {
        $this->message = new Message();
        $this->message->setMessage($this->text);
        $this->message->setDestination($this->destination);
    }

    public function testGetDestination()
    {
        $this->assertEquals(
            $this->destination,
            $this->message->getDestination()
        );
    }

    public function testSetDestination()
    {
        $destination = 1;
        $this->message->setDestination($destination);
        $this->assertEquals(
            $destination,
            $this->message->getDestination()
        );
    }

    public function testGetMessage()
    {
        $this->assertEquals(
            $this->text,
            $this->message->getMessage()
        );
    }

    public function testSetMessage()
    {
        $text = 'test';
        $this->message->setMessage($text);
        $this->assertEquals(
            $text,
            $this->message->getMessage()
        );
    }

    public function testHash()
    {
        $this->assertEquals(
            md5(serialize($this->message)),
            $this->message->hash()
        );
    }

    public function testSerialize()
    {
        $this->assertEquals(
            md5(serialize($this->message)),
            $this->message->hash()
        );
    }

    public function testIsGSM7()
    {
        $this->assertTrue($this->message->isGSM7());

        $this->message->setMessage($this->unicodeText);
        $this->assertFalse($this->message->isGSM7());
    }

    public function testPrepareMessageSingle()
    {
        $result = $this->message->prepareMessage();

        $this->assertCount(1, $result);

        $result = current($result);

        $this->assertArrayHasKey('udh', $result);
        $this->assertArrayHasKey('message', $result);
    }

    public function testPrepareMessageMultiPart()
    {
        $this->message->setMessage(str_repeat('a', Message::GSM7_MSG_LEN + 1));
        $result = $this->message->prepareMessage();

        $this->assertCount(2, $result);

        foreach ($result as $message) {
            $this->assertArrayHasKey('udh', $message);
            $this->assertArrayHasKey('message', $message);
        }
    }

    public function testPrepareMessageMultiPartUnicode()
    {
        $this->message->setMessage(str_repeat('œ', 40));
        $result = $this->message->prepareMessage();

        $this->assertCount(2, $result);

        foreach ($result as $message) {
            $this->assertArrayHasKey('udh', $message);
            $this->assertArrayHasKey('message', $message);
        }
    }

    public function testIsMultiPart()
    {
        //gsm7
        $this->assertFalse($this->message->isMultiPart());

        $this->message->setMessage(str_repeat('a', Message::GSM7_MSG_LEN + 1));
        $this->assertTrue($this->message->isMultiPart());

        //unicode
        $this->message->setMessage($this->unicodeText);
        $this->assertFalse($this->message->isMultiPart());

        $this->message->setMessage(str_repeat('œ', Message::UNICODE_MSG_LEN + 1));
        $this->assertTrue($this->message->isMultiPart());
    }
}