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


    public function find($slug)
    {
        return Service::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        $data['slug'] = strtolower($data['name']);
        return Service::create($data);
    }

    public function update($slug, array $data)
    {
        $service = Service::where('slug', $slug)->first();
        $data['slug'] = strtolower($data['name']);
        return $service->update($data);
    }

    public function delete($slug)
    {
        $service = Service::where('slug', $slug)->first();
        return $service->delete();
    }


}
