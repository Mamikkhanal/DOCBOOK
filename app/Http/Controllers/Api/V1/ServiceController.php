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
       $result = $this->serviceService = $serviceService;
    }


    /**
     * Get all services.
     */
    public function index()
    {
        $result = $this->serviceService->getAllServices();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'No services found'
            ], 404);
        }
        return response ()->json([
            'status' => true,
            'data'=> $result,
        ],200);
    }

    /**
     * Create a new service.
     */
    public function store(ServiceCreateRequest $request)
    {
        $data = $request->validated();

        $result = $this->serviceService->createService($data);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Service creation failed'
            ], 500);
        }
        return response ()->json([
            'status' => true,
            'message' => 'Service created successfully',
        ],201);
    }

    /**
     * Display a specified service.
     */
    public function show(Service $service)
    {
        $result = $this->serviceService->getService($service);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'No service found'
            ], 404);        
        } else {
            return response()->json([
                'status' => true,
                'data' => $result
            ], 200);
        }
    }

    /**
     * Update a specified service.
     */
    public function update(ServiceEditRequest $request, Service $service)
    {
        $data = $request->validated();

        $result = $this->serviceService->updateService($service, $data);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Service update failed'
            ], 500);
        }
        return response()->json([
            'message' => 'Service updated successfully'
        ], 200);
    }

    /**
     * Delete a specified service.
     */
    public function destroy(Service $service)
    {
        $result =$this->serviceService->deleteService($service);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Service deletion failed'
            ], 500);
        }
        return response()->json([
            'message' => 'Service deleted successfully'
        ], 200);
    }
}
