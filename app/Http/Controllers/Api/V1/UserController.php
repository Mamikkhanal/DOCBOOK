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
use App\Http\Requests\AdminAddRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\AdminEditRequest;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a list of users
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
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display a specified user.
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
     * Update the specified user.
     */
    public function update(UserEditRequest $request, string $id)
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
     * Delete the specified user.
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

    /**
     * Add an admin
     */

    public function addAdmin(AdminAddRequest $request)
    {
        $data = $request->validated();
    
        $result = $this->userService->addAdmin($data);

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

    /**
     * Update the specified admin
     */
    public function editAdmin(AdminEditRequest $request, $id)
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
