<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function all()
    {
        return Appointment::with(['doctor.user', 'patient.user', 'service', 'payment'])->orderBy('id', 'desc')->get();
    }

    public function findById($id)
    {
        return Appointment::with(['doctor.user', 'patient.user', 'service', 'payment'])->find($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return $appointment;
    }
}


