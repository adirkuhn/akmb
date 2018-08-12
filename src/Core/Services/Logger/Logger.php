<?php
namespace Akmb\Core\Services\Logger;

use Akmb\Core\ServiceContainer\Interfaces\ServiceInterface;

class Logger implements ServiceInterface
{
    public function __construct()
    {
    }

    public function error($msg): bool
    {
        return $this->log(LOG_ERR, $msg);
    }

    public function warning($msg): bool
    {
        return $this->log(LOG_WARNING, $msg);
    }

    public function info($msg): bool
    {
        return $this->log(LOG_INFO, $msg);
    }

    protected function log($priority, $msg): bool
    {
        return syslog($priority, $msg);
    }

    public function getServiceIdentifier(): string
    {
        return self::class;
    }
}
