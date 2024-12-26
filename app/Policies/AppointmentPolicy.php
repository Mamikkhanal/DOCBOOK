<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\support\Facades\Auth;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if($appointment->patient->user->id === Auth::user()->id || $appointment->doctor->user->id === Auth::user()->id || Auth::user()->role === 'admin') {
            
            return true;
        }
        else{

            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Auth::user()->role === 'patient' || Auth::user()->role === 'admin') {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if($appointment->doctor->user->id === Auth::user()->id || Auth::user()->role === 'admin') {
            
            return true;
        }
        else{

            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        if($appointment->doctor->user->id === Auth::user()->id || Auth::user()->role === 'admin') {
            if($appointment->status === 'pending') { 
                return true;
            }
            else{
                return false;
            }
        }
        else{

            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return true;
    }

}
