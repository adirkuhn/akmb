<?php
namespace Akmb\Core\Libs\Redis\Interfaces;


interface RedisConfigurationInterface
{
    public function getHost(): string;
    public function getPort(): string;
    public function getUser(): string;
    public function getPassword(): string;
}