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
         
        if(empty($existingSchedules)){
            return true;
        };

        foreach ($existingSchedules as $schedule) {

            $schedule_start_time = Carbon::parse($schedule->start_time);
            $schedule_end_time = Carbon::parse($schedule->end_time);

            $data_start_time = Carbon::parse($data['start_time']);
            $data_end_time = Carbon::parse($data['end_time']);

            if (Carbon::parse($schedule->date)->format('d-m-Y') == Carbon::parse($data['date'])->format('d-m-Y')) {
                if (
                   ($data_start_time)->between($schedule_start_time, $schedule_end_time) ||
                    ($data_end_time)->between($schedule_start_time, $schedule_end_time) ||
                    ($schedule_start_time)->between($data_start_time, $data_end_time) ||
                    ($schedule_end_time)->between($data_start_time, $data_end_time)
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    public function createSchedule($data)
    {
        $doctorId = Doctor::where('user_id', Auth::id())->first()->id;

        $result = $this->validateSchedule($data, $doctorId);

        $data['doctor_id'] = $doctorId;

        if ($result==false) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Schedule already exists'
                ],422      
             );
        }

        $this->scheduleRepository->create($data);

        return response()->json(
            [
                'status' => true,
                'message' => 'Schedule created successfully'
            ],201
        );

    }

    public function getScheduleById($schedule)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {

            $schedule= $this->scheduleRepository->findById($schedule->id);

            return response()->json(
                [
                    'status' => true,
                    'data' => $schedule
                ],200
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to view this schedule.'
                ]
            );
        }
    }

    public function updateSchedule($schedule, $data)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {

            $doctorId = Doctor::where('user_id', Auth::id())->first()->id;

           $result = $this->validateSchedule($data, $doctorId);

            if ($result==false) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Schedule already exists'
                    ],422      
                 );
            }

            $this->scheduleRepository->update($schedule, $data);

            return response ()->json(
                [
                    'status' => true,
                    'message' => 'Schedule updated successfully'
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to update this schedule.'
                ],
                403
            );
        }
    }

    public function deleteSchedule($schedule)
    {
        if (Auth::user()->role == 'doctor' && $schedule->doctor_id == Doctor::where('user_id', Auth::id())->first()->id) {

            $this->scheduleRepository->delete($schedule);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Schedule deleted successfully'
                ],
                200
            );

        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to delete this schedule.'
                ],
                403
            );
        }
    }
}
