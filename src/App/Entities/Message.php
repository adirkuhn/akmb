<?php
namespace Akmb\App\Entities;

class Message
{
    const GSM7_MSG_LEN = 160;
    const UNICODE_MSG_LEN = 70;
    const GSM7_MSG_PART_LEN = 153;
    const UNICODE_MSG_PART_LEN = 63;

    const UNICODE = 'UNICODE';
    const GSM7 = 'GSM7';

    /**
     * @var string $destination
     */
    private $destination;

    /**
     * @var string $message
     */
    private $message;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
        $this->type = ($this->isGSM7()) ? self::GSM7 : self::UNICODE;
    }

    /**
     * @return string
     */
    public function hash(): string
    {
        return md5($this->serialize());
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this);
    }

    public function prepareMessage(): array
    {
        $messages = [];
        $msgId = rand(0, 255);
        if ($this->isMultiPart()) {
            $parts = $this->splitMessage();
            $total = count($parts);
            foreach ($parts as $k => $part) {
                $messages[] = [
                    'udh' => $this->createUdh($msgId, $total, ($k+1)),
                    'message' => $this->encodeMessage($part)
                ];
            }
        } else {
            $messages[] = [
                'udh' => $this->createUdh($msgId, 1, 1),
                'message' => $this->encodeMessage($this->getMessage())
            ];
        }

        return $messages;
    }

    public function isMultiPart(): bool
    {
        $maxLen = self::GSM7_MSG_LEN;
        if ($this->type === self::UNICODE) {
            $maxLen = self::UNICODE_MSG_LEN;
        }

        return strlen($this->getMessage()) > $maxLen;
    }

    public function isGSM7()
    {
        return (preg_match(
            '/^[\x{20}-\x{7E}£¥èéùìòÇ\rØø\nÅåΔ_ΦΓΛΩΠΨΣΘΞ\x{1B}ÆæßÉ ¤¡ÄÖÑÜ§¿äöñüà\x{0C}€]*$/u',
            $this->getMessage()
            ) === 1);
    }

    private function splitMessage()
    {
        $len = self::GSM7_MSG_PART_LEN;

        if ($this->type === self::UNICODE) {
            $len = self::UNICODE_MSG_PART_LEN;
        }

        return str_split($this->getMessage(), $len);
    }

    /**
     * @param int $msgId
     * @param int $total
     * @param int $part
     * @return string
     */
    private function createUdh(int $msgId, int $total, int $part): string
    {
        return sprintf(
            '%02x%02x%02x%02x%02x%02x',
            5,
            0,
            3,
            $msgId,
            $total,
            $part
        );
    }

    /**
     * @param string $message
     * @return string
     */
    private function encodeMessage(string $message): string
    {
        return bin2hex($message);
    }
}