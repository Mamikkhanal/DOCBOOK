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

        return response()->json(
            [
                'success' => true,
                'data' => $result,
            ],
            200
        );
    }

    /**
     * Display all appointments according to roles
     */
    public function index()
    {
        $result = $this->appointmentService->getAllAppointments();

        if (!$result) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No appointments found.'
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => $result
            ],
            200
        );
    }

    /**
     * Store a newly created appointment.
     */

    public function store(AppointmentCreateRequest $request)
    {

        $result = $this->appointmentService->createAppointment($request);

        if (!$result) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Appointment creation failed'
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Appointment created successfully',
            ],
            201
        );
    }

    /**
     * Display the specified appointment.
     */
    public function show(string $id)
    {
        $result = $this->appointmentService->getAppointment($id);

        if (!$result) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Appointment not found'
                ],
                404
            );
        }   

        return response()->json(
            [
                'success' => true,
                'data' => $result
            ],
            200     
        );
    }

    /**
     * Update the specified appointment.
     */
    public function update(AppointmentEditRequest $request, string $id)
    {
        $result = $this->appointmentService->updateAppointment($request, $id);

        if (!$result) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Appointment update failed'
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Appointment updated successfully',
            ],
            200
        );
    }

    /**
     * Delete the specified appointment.
     */
    public function destroy(string $id)
    {
        $result = $this->appointmentService->deleteAppointment($id);

        if (!$result) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Appointment deletion failed'
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Appointment deleted successfully',
            ],
            200 
        );
    }
}
