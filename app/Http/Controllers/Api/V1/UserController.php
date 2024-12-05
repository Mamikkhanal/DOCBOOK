<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Services\RegisterService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // {
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role'=>$request->role,
    //         'phone'=>$request->phone
    //     ]);

    //     $token = $user->createToken('API Token')->plainTextToken;

    //     if ($request->role == 'patient') {
    //         Patient::create([
    //             'user_id' => $user->id,
    //             'age'=>$request->age,
    //         ]);
    //         return response()->json(['message' => 'User registered successfully as a patient', 'token' => $token], 201);
    //     }
    //     elseif ($request->role == 'doctor') {
    //         Doctor::create([
    //             'user_id' => $user->id,
    //             'specialization'=>$request->specialization,
    //         ]);
    //         return response()->json(['message' => 'User registered successfully as a doctor', 'token' => $token], 201);
    //     }

    //     return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    // }

 
}
