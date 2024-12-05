<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'status' => true,
                'message' => 'Login successful',
                'data' => ['token' => $token],
            ];
        }

        return [
            'status' => false,
            'message' => 'Invalid credentials',
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();

        return [
            'status' => true,
            'message' => 'Logged out successfully',
        ];
    }
}
