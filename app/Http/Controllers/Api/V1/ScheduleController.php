<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleCreateRequest;
use App\Http\Requests\ScheduleEditRequest;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Get all scheudles.
     */
    public function index()
    {
        $schedules = $this->scheduleService->getSchedulesForDoctor();

        if (!$schedules || $schedules->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No schedules found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'schedules' => $schedules
        ], 200);
    }

    /**
     * Create a new schedule.
     */
    public function store(ScheduleCreateRequest $request)
    {
        $data = $request->validated();
        try {
            $result = $this->scheduleService->createSchedule($data);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Schedule creation failed'
                ], 400);
            }
            return $result;
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display a specified schedule.
     */
    public function show(Schedule $schedule)
    {
        $result = $this->scheduleService->getScheduleById($schedule);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Schedule not found'
            ], 404);
        }
        return $result;
    }

    /**
     * Update a specified scheudule.
     */
    public function update(ScheduleEditRequest $request, $id)
    {
        $data = $request->validated();

        $schedule = $this->scheduleService->findById($id);

        try {
            $result = $this->scheduleService->updateSchedule($schedule, $data);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Schedule update failed'
                ], 400);
            }

            return $result;
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a specified schedule.
     */
    public function destroy($id)
    {
        $schedule = $this->scheduleService->findById($id);

        try {
            $result = $this->scheduleService->deleteSchedule($schedule);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Schedule deletion failed'
                ], 400);
            }
            return $result;
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
