<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected  $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
    public function specializations()
    {
        return $this->hasOne(Specialization::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($doctor) {
            // Ensure the associated User is deleted when the Doctor is deleted
            if ($doctor->user) {
                $doctor->user->delete();
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

    public function getFormattedNameAttribute()
    {
        return "{$this->name} ({$this->specialization})";
    }
    
}
