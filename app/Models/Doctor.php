<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected  $guarded = [];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($doctor) {
            $doctor->slug = Str::random(16);
        });

        static::deleting(function ($doctor) {
            if ($doctor->user) {
                $doctor->user->delete();
            }
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }


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
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
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
