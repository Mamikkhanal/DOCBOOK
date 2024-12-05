<?php 

namespace App\Repositories;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;

class UserRepository
{
    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function createPatient(array $data)
    {
        return Patient::create($data);
    }

    public function createDoctor(array $data)
    {
        return Doctor::create($data);
    }
}
