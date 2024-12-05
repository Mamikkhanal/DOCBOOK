<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentCreateRequest;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->appointmentService->getAllAppointments();

        return response()->json($result); 
    }
    
    /**
     * Store a newly created resource in storage.
     */
    
    public function store(AppointmentCreateRequest $request)
    {

        $result = $this->appointmentService->createAppointment($request);

        return response()->json($result);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = $this->appointmentService->getAppointment($id);

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->appointmentService->updateAppointment($request, $id);

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->appointmentService->deleteAppointment($id);

        return response()->json($result);
    }
}
