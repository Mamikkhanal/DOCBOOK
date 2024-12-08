<?php

namespace App\Services;

use App\Models\Specialization;
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

        DB::beginTransaction();

        try {

            $user = $this->userRepository->createUser([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'phone' => $data['phone'],
            ]);

            if ($data['role'] === 'patient') {

                $this->userRepository->createPatient([
                    'user_id' => $user->id,
                    'age' => $data['age'],
                ]);

            } elseif ($data['role'] === 'doctor') {

                $specialization =  Specialization::all();
                $request = false;
                foreach ($specialization as $spec) {
                    if ($spec->name === $data['specialization']) {
                        return $request=(true);
                    }
                }
                if (!$request) {
                    $this->userRepository->createDoctor([
                        'user_id' => $user->id,
                        'specialization' => $data['specialization'],
                    ]);  
                }
            }

            DB::commit();

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
            
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'User registration failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
