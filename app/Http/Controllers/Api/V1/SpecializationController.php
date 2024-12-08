<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use App\Services\SpecializationService;
use App\Http\Requests\SpecializationEditRequest;
use App\Http\Requests\SpecializationCreateRequest;

class SpecializationController extends Controller
{
    protected $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    /**
     * Get all specializations.
     */

    public function index()
    {
        $specializations = $this->specializationService->getAllSpecializations();

        if (!$specializations) {
            return response()->json([
                'status' => false,
                'message' => 'No specializations found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $specializations
        ], 200);
    }

    /**
     * Create a new specialization.
     */
    public function store(SpecializationCreateRequest $request)
    {
        $data = $request->validated();
        
        $specialization = $this->specializationService->createSpecialization($data);

        if (!$specialization) {
            return response()->json([
                'status' => false,
                'message' => 'Specialization creation failed'
            ], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Specialization created successfully'
        ], 201);
    }


    /**
     * Display a specified specialization.
     */
    public function show(Specialization $specialization)
    {
        $specialization = $this->specializationService->getSpecialization($specialization);

        if (!$specialization) {
            return response()->json([
                'status' => false,
                'message' => 'Specialization not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'specialization' => $specialization
        ], 200);
    }

    /**
     * Update a specified specialization.
     */
    public function update(SpecializationEditRequest $request, Specialization $specialization)
    {
        $specialization = $this->specializationService->updateSpecialization($specialization, $request['data']);

        if (!$specialization) {
            return response()->json([
                'status' => false,
                'message' => 'Specialization update failed'
            ], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Specialization updated successfully'
        ], 200);
    }

    /**
     * Delete a specified specialization.
     */
    public function destroy(Specialization $specialization)
    {
        $specialization =  $this->specializationService->deleteSpecialization($specialization);

        if (!$specialization) {
            return response()->json([
                'status' => false,
                'message' => 'Specialization deletion failed'
            ], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Specialization deleted successfully'
        ], 200);
    }
}
