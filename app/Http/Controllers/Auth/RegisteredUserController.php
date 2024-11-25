<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'=>['required', 'in:patient,doctor'],
            'phone' => ['required', 'string', 'min:10'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if(Auth::user()->role ==('doctor')) {
        return redirect(route('doc_details'));
        }
        return redirect(route('pat_details'));
    }

    public function add_details(){

        if(Auth::user()->role ==('doctor')) {
            $specializations = Specialization::all();
            return view('doctor.doc_details', compact('specializations'));
        }
        elseif(Auth::user()->role == 'patient') {
        return view('patient.pat_details');
        }
        else{
            return view('welcome');
        }
    }

    public function admins(){
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins', compact('admins'));
    }

    public function adminsDelete($id){
        $admin = User::find($id);
        $admin->delete();
        return redirect()->route('admins');
    }

}
