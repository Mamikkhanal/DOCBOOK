<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\PatientService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatientEditRequest;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->patientService->getAllPatients();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $result
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $result = $this->patientService->createPatient($request->all());

        // if (!$result) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Patient not found',
        //     ], 404);
        // }

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Patient created successfully',
        //     'data' => $result
        // ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = $this->patientService->getPatient($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found',
            ], 404);
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientEditRequest $request, string $id)
    {
        $result = $this->patientService->updatePatient($id, $request->all());

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found',
            ], 404);
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->patientService->deletePatient($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Patient not found',
            ], 404);
        }

        return $result;
    }
}
