<?php
namespace Akmb\App\Entities;

class Message
{
    const GSM7_MSG_LEN = 160;
    const UNICODE_MSG_LEN = 70;

    /**
     * @var string $destination
     */
    private $destination;

    /**
     * @var string $message
     */
    private $message;

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
        if ($this->isMultiPart()) {

        } else {
            return $this->getMessage();
        }
    }

    public function isMultiPart(): bool
    {
        return strlen($this->getMessage()) > 160;
    }

    function isGSM7() {
        return (preg_match(
            '/^[\x{20}-\x{7E}£¥èéùìòÇ\rØø\nÅåΔ_ΦΓΛΩΠΨΣΘΞ\x{1B}ÆæßÉ ¤¡ÄÖÑÜ§¿äöñüà\x{0C}€]*$/u',
            $this->getMessage()
            ) === 1);
    }
}