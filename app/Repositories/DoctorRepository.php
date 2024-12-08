<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
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
        return Doctor::all();
    }

    public function find($id)
    {
        return Doctor::find($id);
    }

    public function create(array $data)
    {
        return Doctor::create($data);
    }

    public function update($id, array $data)
    {
        return Doctor::find($id)->update($data);
    }

    public function delete($id)
    {
        return Doctor::find($id)->delete();
    }
}
