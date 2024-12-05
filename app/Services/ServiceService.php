<?php

namespace App\Services;

use App\Repositories\ServiceRepository;

class ServiceService
{
    /**
     * Create a new class instance.
     */
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllServices()
    {
        return $this->serviceRepository->getAll();
    }

    public function createService($data)
    {
        
        return $this->serviceRepository->create($data);
    }

    public function updateService($service, $data)
    {
        return $this->serviceRepository->update($service, $data);
    }

    public function deleteService($service)
    {
        return $this->serviceRepository->delete($service);
    }
}
