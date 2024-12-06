<?php

namespace App\Repositories;

use App\Models\Schedule;

class ScheduleRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function getDoctorSchedules($doctorId)
    {
        if ($doctorId == null) {
            return Schedule::all();
        }
        return Schedule::where('doctor_id', $doctorId)->get();
    }

    public function findById($id)
    {
        return Schedule::findOrFail($id);
    }

    public function create(array $data)
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data)
    {
        return $schedule->update($data);
    }

    public function delete(Schedule $schedule)
    {
        return $schedule->delete();
    }
}
