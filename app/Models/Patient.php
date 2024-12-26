<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected  $guarded = [];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            $user->slug = Str::random(16);
        });

        static::deleting(function ($patient) {
            // Ensure the associated User is deleted when the Patient is deleted
            if ($patient->user) {
                $patient->user->delete();
            }
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
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
