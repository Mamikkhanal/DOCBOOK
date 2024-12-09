<?php

namespace App\Repositories;

use App\Models\Specialization;

class SpecializationRepository
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
        return Specialization::all();
    }

    public function find($slug)
    {
        return Specialization::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        $data['slug'] = strtolower($data['name']);
        return Specialization::create($data);
    }

    public function update($slug, array $data)
    {
        $specialization = Specialization::where('slug', $slug)->first();
        return $specialization->update($data);
    }

    public function delete($slug)
    {
        $specialization = Specialization::where('slug', $slug)->first();
        return $specialization->delete();
    }
}
