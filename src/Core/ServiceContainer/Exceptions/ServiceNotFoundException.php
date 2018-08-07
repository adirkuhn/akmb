<?php
namespace Akmb\Core\ServiceContainer\Exceptions;

class ServiceNotFoundException extends \Exception
{
    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var string $code
     */
    protected $code;

    public function __construct(string $msg)
    {
        $this->message = sprintf('Service [%s] was not found.', $msg);
        $this->code = 3;

        parent::__construct($this->message, $this->code);
    }
}
