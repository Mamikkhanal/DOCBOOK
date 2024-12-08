<?php

namespace App\Repositories;

use App\Models\Patient;

class PatientRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function all()
    {
        return Patient::all();
    }

    public function find($id)
    {
        return Patient::find($id);
    }

    public function create(array $data)
    {
        return Patient::create($data);
    }

    public function update($id, array $data)
    {
        return Patient::find($id)->update($data);
    }

    public function delete($id) {
        return Patient::find($id)->delete();
    }
}
