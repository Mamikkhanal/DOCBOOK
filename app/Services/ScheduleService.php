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
        if (Auth::user()->role == 'doctor') {
            $doctorId = Doctor::where('user_id', Auth::id())->first()->id;
            return $this->scheduleRepository->getDoctorSchedules($doctorId);
        } else {
            return $this->scheduleRepository->getDoctorSchedules(null);
        }
    }

    public function validateSchedule($data, $doctorId)
    {
        $existingSchedules = $this->scheduleRepository->getDoctorSchedules($doctorId);
        foreach ($existingSchedules as $schedule) {
            if (Carbon::parse($schedule->date)->format('d-m-Y') == Carbon::parse($data['date'])->format('d-m-Y')) {
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

    public function getScheduleById($schedule)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {
            return $this->scheduleRepository->findById($schedule->id);
        } else {
            return response()->json(['message' => 'You are not authorized to view this schedule.'], 403);
        }
    }

    public function updateSchedule($schedule, $data)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {
            $doctorId = Doctor::where('user_id', Auth::id())->first()->id;
            $this->validateSchedule($data, $doctorId);
            return $this->scheduleRepository->update($schedule, $data);
        } else {
            return response()->json(['message' => 'You are not authorized to update this schedule.'], 403);
        }
    }

    public function deleteSchedule($schedule)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {
        return $this->scheduleRepository->delete($schedule);
    } else {
        return response()->json(['message' => 'You are not authorized to delete this schedule.'], 403);
    }
    }
}
