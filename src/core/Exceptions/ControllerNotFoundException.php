<?php
namespace Akmb\Core\Exceptions;

class ControllerNotFoundException extends \Exception
{
    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var string $code
     */
    protected $code;

    public function __construct()
    {
        $this->message = 'Controller was not found.';
        $this->code = 1;

        parent::__construct($this->message, $this->code);
    }
}
