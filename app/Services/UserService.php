<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getUsers()
    {
        if(Auth::user()->role == 'admin'){
            $users = User::all();
            return $users;
        }
        else{
            return null;
        }

    }

    public function getUserById($id)
    {
        if(Auth::user()->role == 'admin'|| Auth::user()->id == $id){
            $user = User::find($id);
            return $user;
        }
        else{
            return null;
        }
    }

    public function updateUser($request, $id)
    {
        if(Auth::user()->id == $id){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email =  $request->email;
            $user->password =  $request->password;
            $user->phone =  $request->phone;
            $user->update();
            return $user;
        }
        else{
            return null;
        }
    }

    public function deleteUser($id)
    {
        if(Auth::user()->id == $id){
            $user = User::find($id);
            $user->delete();
            return $user;
        }
        else{
            return null;
        }
    }

    public function addAdmin($request)
    {
        if(Auth::user()->role == 'admin'){
            $user = new User();
            $user->name = $request->name;
            $user->email =  $request->email;
            $user->password =  $request->password;
            $user->phone =  $request->phone;
            $user->role = 'admin';
            $user->save();
            return $user;
        }
        else{
            return null;
        }
    }

    public function editAdmin($request,$id)
    {
     if(Auth::user()->role == 'admin'){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email =  $request->email;
            $user->password =  $request->password;
            $user->phone =  $request->phone;
            $user->role = 'admin';
            $user->update();
            return $user;
        }
        else{
            return null;
        }
        

    }
}
