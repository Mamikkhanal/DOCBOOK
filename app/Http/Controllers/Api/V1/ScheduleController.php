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
        return response()->json(['schedules' => $schedules], 200);
    }

    /**
     * Create a new schedule.
     */
    public function store(ScheduleCreateRequest $request)
    {
        $data = $request->validated();
        try {
            $schedule = $this->scheduleService->createSchedule($data);
            return response()->json(['message' => 'Schedule created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display a specified schedule.
     */
    public function show(Schedule $schedule)
    {
        return response()->json(['schedule' => $schedule], 200);
    }

    /**
     * Update a specified scheudule.
     */
    public function update(ScheduleEditRequest $request, $id)
    {
        $data = $request->validated();

        $schedule = $this->scheduleService->findById($id);

        try {
            $this->scheduleService->updateSchedule($schedule, $data);
            return response()->json(['message' => 'Schedule updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete a specified schedule.
     */
    public function destroy($id)
    {
        $schedule = $this->scheduleService->findById($id);

        try {
            $this->scheduleService->deleteSchedule($schedule);
            return response()->json(['message' => 'Schedule deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
