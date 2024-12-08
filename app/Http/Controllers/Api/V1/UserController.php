<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RegisterService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->userService->getUsers();

        if ($result) {
            return response()->json([
                'status' => true,
                'data' => $result
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $result = $this->userService->getUserByID($id);

        if ($result) {
            return response()->json([
                'status' => true,
                'data' => $result
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->userService->updateUser($request, $id);

        if ($result) {
            return response()->json([
                'status' => true,
                "message" => "User updated successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->userService->deleteUser($id);

        if ($result) {
            return response()->json([
                'status' => true,
                "message" => "User deleted successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }

    public function addAdmin(Request $request)
    {
        $result = $this->userService->addAdmin($request);

        if ($result) {
            return response()->json([
                'status' => true,
                "message" => "Admin added successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }

    public function editAdmin(Request $request, $id)
    {
        $result = $this->userService->editAdmin($request, $id);

        if ($result) {
            return response()->json([            
                'status' => true,
                "message" => "Admin updated successfully",
            ], 200);        
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized.'
            ], 404);
        }
    }   
}
