<?php
namespace Akmb\Core\Extra;

class Logger
{
    public function __construct()
    {
    }

    public function error($msg): void
    {
        $this->log(LOG_ERR, $msg);
    }

    public function warning($msg): void
    {
        $this->log(LOG_WARNING, $msg);
    }

    public function info($msg): void
    {
        $this->log(LOG_INFO, $msg);
    }

    protected function log($priority, $msg): void
    {
        syslog($priority, $msg);
    }
}
