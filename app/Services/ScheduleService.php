<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ScheduleRepository;

class ScheduleService
{
    protected $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function findById($id)
    {
        return $this->scheduleRepository->findById($id);
    }


    public function getSchedulesForDoctor()
    {
        $doctorId = Doctor::where('user_id', Auth::id())->first()->id;
        return $this->scheduleRepository->getDoctorSchedules($doctorId);
    }

    public function validateSchedule($data, $doctorId)
    {
        $existingSchedules = $this->scheduleRepository->getDoctorSchedules($doctorId);
        foreach ($existingSchedules as $schedule) {
            if ($schedule->date == Carbon::parse($data['date'])->format('d-m-Y')) {
                if (
                    Carbon::parse($data['start_time'])->between($schedule->start_time, $schedule->end_time) ||
                    Carbon::parse($data['end_time'])->between($schedule->start_time, $schedule->end_time)
                ) {
                    throw new \Exception('Schedule conflicts with an existing schedule.');
                }
            }
        }
    }

    public function createSchedule($data)
    {
        $doctorId = Doctor::where('user_id', Auth::id())->first()->id;
        $this->validateSchedule($data, $doctorId);
        $data['doctor_id'] = $doctorId;
        return $this->scheduleRepository->create($data);
    }

    public function updateSchedule($schedule, $data)
    {
        $doctorId = Doctor::where('user_id', Auth::id())->first()->id;
        $this->validateSchedule($data, $doctorId);
        return $this->scheduleRepository->update($schedule, $data);
    }

    public function deleteSchedule($schedule)
    {
        return $this->scheduleRepository->delete($schedule);
    }
}
