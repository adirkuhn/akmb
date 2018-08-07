<?php
namespace Akmb\Core\ServiceContainer;

use Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException;
use Akmb\Core\ServiceContainer\Interfaces\ServiceInterface;

class ServiceContainer
{
    private $services = [];

    public function has(string $serviceIdentifier): bool
    {
        return isset($this->services[$serviceIdentifier]);
    }

    /**
     * @param string $serviceIdentifier
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function getService(string $serviceIdentifier): ServiceInterface
    {
        if (!$this->has($serviceIdentifier)) {
            throw new ServiceNotFoundException($serviceIdentifier);
        }

        return $this->services[$serviceIdentifier];
    }

    public function addService(ServiceInterface $service): void
    {
        $this->services[$service->getServiceIdentifier()] = $service;
    }
}
