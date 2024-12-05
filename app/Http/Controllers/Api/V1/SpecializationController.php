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

    public function index()
    {
        $specializations = $this->specializationService->getAllSpecializations();
        return response()->json(['specializations' => $specializations], 200);
    }

    public function store(SpecializationCreateRequest $request)
    {
        $data = $request->validated();
        $specialization = $this->specializationService->createSpecialization($data);

        return response()->json(['message' => 'Specialization created successfully'], 201);
    }

    public function show(Specialization $specialization)
    {
        return response()->json(['specialization' => $specialization], 200);
    }

    public function update(SpecializationEditRequest $request, Specialization $specialization)
    {
        $data = $request->validated();
        $this->specializationService->updateSpecialization($specialization, $data);

        return response()->json(['message' => 'Specialization updated successfully'], 200);
    }

    public function destroy(Specialization $specialization)
    {
        $this->specializationService->deleteSpecialization($specialization);
        return response()->json(['message' => 'Specialization deleted successfully'], 200);
    }
}
