<?php
namespace Akmb\Core\Libs;

class Redis
{
    public function __construct()
    {
    }

    public function saveData(string $id, array $data): array
    {
        //save to redis
        return $data;
    }

    public function getData(string $id): array
    {
        // get from redis
        return [];
    }
}