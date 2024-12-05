<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Service::all();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data)
    {
        return $service->update($data);
    }

    public function delete(Service $service)
    {
        return $service->delete();
    }


}
