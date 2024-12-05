<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $data)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Create User
            $user = $this->userRepository->createUser([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'phone' => $data['phone'],
            ]);

            // Role-specific logic
            if ($data['role'] === 'patient') {
                $this->userRepository->createPatient([
                    'user_id' => $user->id,
                    'age' => $data['age'],
                ]);
            } elseif ($data['role'] === 'doctor') {
                $this->userRepository->createDoctor([
                    'user_id' => $user->id,
                    'specialization' => $data['specialization'],
                ]);
            }

            // Commit transaction
            DB::commit();

            // Generate token
            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ];
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'User registration failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
