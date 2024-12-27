<?php

namespace App\Policies;

use App\Models\Specialization;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Auth\Access\Response;

class SpecializationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->role == 'admin') {
            
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Specialization $specialization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Specialization $specialization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Specialization $specialization): bool
    {
        if($specialization->doctors->count() > 0) {
            // Notification::make()
            // ->danger()
            // ->title('Cannot Delete')
            // ->body('Cannot delete specialization because it is associated with at least one doctor')
            // ->send();
            return false;

        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Specialization $specialization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Specialization $specialization): bool
    {
        return true;
    }
}
