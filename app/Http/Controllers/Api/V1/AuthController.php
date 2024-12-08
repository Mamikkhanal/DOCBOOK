<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\RegisterService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    protected $registerService;
    protected $authService;

    public function __construct(RegisterService $registerService, AuthService $authService)
    {
        $this->registerService = $registerService;
        $this->authService = $authService;
    }

    /**
     * User Registration.
     */
    public function register(UserRegisterRequest $request)
    {
        $result = $this->registerService->registerUser($request['data']);

        if ($result['status']) {
            return response()->json([
                'message' => $result['message'],
                'token' => $result['data']['token'],
            ], 201);
        }

        return response()->json([
            'message' => $result['message'],
            'error' => $result['error'],
        ], 500);
    }

    /**
     * User Login.
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        if ($result['status']) {
            return response()->json([
                'message' => $result['message'],
                'token' => $result['data']['token'],
            ], 200);
        }

        return response()->json(
        ['message' => $result['message']
        ],401);
    }

    /**
     * User Logout.
     */
    public function logout(Request $request)
    {
        $result = $this->authService->logout($request->user());

        return response()->json([
            'message' => $result['message']
        ], 200);
    }
}
