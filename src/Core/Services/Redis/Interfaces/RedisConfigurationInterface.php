<?php
namespace Akmb\Core\Services\Redis\Interfaces;


interface RedisConfigurationInterface
{
    public function getScheme(): string;
    public function getHost(): string;
    public function getPort(): string;
    public function getUser(): string;
    public function getPassword(): string;
}