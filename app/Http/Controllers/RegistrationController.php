<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

public function showRegisterForm()
{
    $specializations = Specialization::all();
    return view('register', compact('specializations'));
}

public function register(UserRegisterRequest $request)
{
    $validated = $request->validated();

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'phone' => $validated['phone'],
        'role' => $validated['role'],
    ]);
    
    Auth::login($user);
    
    if ($validated['role'] === 'doctor') {
        $user->doctor()->create([
            'user_id' => Auth::user()->id,
            'specialization' => $validated['specialization'],
        ]);
        
    } elseif ($validated['role'] === 'patient') {
        $user->patient()->create([
            'user_id' => Auth::user()->id,
            'age' => $validated['age'],
        ]);
    }

    return redirect()->route('filament.admin.pages.dashboard'); // Adjust as needed
}

}
