<?php
namespace Akmb\App\Entities;

class Message
{
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
}