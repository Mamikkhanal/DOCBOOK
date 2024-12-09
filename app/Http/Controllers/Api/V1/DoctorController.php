<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Services\DoctorService;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorEditRequest;

class DoctorController extends Controller
{
    protected $doctorService;
    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->doctorService->getAllDoctors();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'No doctors found'
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
    //     $result = $this->doctorService->createDoctor($request->all());

    //     if (!$result) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Doctor creation failed'
    //         ], 500);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Doctor created successfully',
    //         'data' => $result
    //     ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = $this->doctorService->getDoctor($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'No doctor found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorEditRequest $request, string $id)
    {
        $result = $this->doctorService->updateDoctor($id, $request->all());

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor update failed'
            ], 500);
        }
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->doctorService->deleteDoctor($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Doctor deletion failed'
            ], 500);
        }
        return $result;
    }
}
