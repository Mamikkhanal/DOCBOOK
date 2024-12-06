<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\ServiceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCreateRequest;
use App\Http\Requests\ServiceEditRequest;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }


    /**
     * Get all services.
     */
    public function index()
    {
        $services = $this->serviceService->getAllServices();
        return response()->json([
            'services' => $services
        ], 200);
    }

    /**
     * Create a new service.
     */
    public function store(ServiceCreateRequest $request)
    {
        $data = $request->validated();

        $service = $this->serviceService->createService($data);
        return response()->json([
            'message' => 'Service created successfully',
        ], 201);
    }

    /**
     * Display a specified service.
     */
    public function show(Service $service)
    {
        return response()->json([
            'service' => $service
        ], 200);
    }

    /**
     * Update a specified service.
     */
    public function update(ServiceEditRequest $request, Service $service)
    {
        $data = $request->validated();

        $this->serviceService->updateService($service, $data);
        return response()->json([
            'message' => 'Service updated successfully'
        ], 200);
    }

    /**
     * Delete a specified service.
     */
    public function destroy(Service $service)
    {
        $this->serviceService->deleteService($service);
        return response()->json([
            'message' => 'Service deleted successfully'
        ], 200);
    }
}