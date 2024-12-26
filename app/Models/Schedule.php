<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected  $guarded = [];

    protected $casts = [
        'date' => 'date:d-m-Y',
        'start_time' => 'datetime:H:i', // Cast to time format
        'end_time' => 'datetime:H:i',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($schedule) {
            $schedule->slug = Str::random(16);
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }



    public function slots(){
        return $this->hasMany(Slot::class);
    }
}
