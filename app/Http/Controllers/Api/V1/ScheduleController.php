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

        if (!$schedules) {
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
            $schedule = $this->scheduleService->createSchedule($data);

            if (!$schedule) {
                return response()->json([
                    'status' => false,
                    'message' => 'Schedule creation failed'
                ], 400);
            }
            return response()->json([
                'status' => true,
                'message' => 'Schedule created successfully'
            ], 201);
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
        return response()->json([
            'status' => true,
            'data' => $result
        ], 200);
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

            return response()->json([
                'status' => true,
                'message' => 'Schedule updated successfully',
            ], 200);
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
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Schedule deleted successfully',
                    'data' => [$result]
                ],
                200
            );
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
