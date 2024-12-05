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

    public function find($id)
    {
        return Specialization::findOrFail($id);
    }

    public function create(array $data)
    {
        return Specialization::create($data);
    }

    public function update(Specialization $specialization, array $data)
    {
        return $specialization->update($data);
    }

    public function delete(Specialization $specialization)
    {
        return $specialization->delete();
    }
}
