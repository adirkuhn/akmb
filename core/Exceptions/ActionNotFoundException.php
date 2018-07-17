<?php
namespace Akmb\Core\Exceptions;

class ActionNotFoundException extends \Exception
{
    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var string $code
     */
    protected $code;

    public function __construct(string $controller)
    {
        $this->message = 'Action not found for controller ' . $controller;
        $this->code = 2;

        parent::__construct($this->message, $this->code);
    }
}
