<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected  $guarded = [];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($patient) {
            // Ensure the associated User is deleted when the Patient is deleted
            if ($patient->user) {
                $patient->user->delete();
            }
        });
    }

   /**
     * Scope a query to include only users created in the current week.
     */
    public function scopeCreatedCurrentWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to include only users created in the past week.
     */
    public function scopeCreatedPastWeek($query)
    {
        return $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
    }
}
