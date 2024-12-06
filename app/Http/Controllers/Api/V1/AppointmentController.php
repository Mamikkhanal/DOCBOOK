<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentCreateRequest;
use App\Http\Requests\AppointmentEditRequest;
use Illuminate\Http\Request;
use App\Services\AppointmentService;
use Auth;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Search for appointments
     */
    public function search(Request $request)
    {
        $request->validate(['search' => 'required|string']);
        $result = $this->appointmentService->searchAppointments($request);
        return response()->json($result);
    }
    
    /**
     * Display all appointments according to roles
     */
    public function index()
    {
        $result = $this->appointmentService->getAllAppointments();

        return response()->json($result); 
    }
    
    /**
     * Store a newly created appointment.
     */
    
    public function store(AppointmentCreateRequest $request)
    {

        $result = $this->appointmentService->createAppointment($request);

        return response()->json($result);

    }

    /**
     * Display the specified appointment.
     */
    public function show(string $id)
    {
        $result = $this->appointmentService->getAppointment($id);

        return response()->json($result);
    }

    /**
     * Update the specified appointment.
     */
    public function update(AppointmentEditRequest $request, string $id)
    {
        $result = $this->appointmentService->updateAppointment($request, $id);

        return response()->json($result);
    }

    /**
     * Delete the specified appointment.
     */
    public function destroy(string $id)
    {
        $result = $this->appointmentService->deleteAppointment($id);

        return response()->json($result);
    }
}
