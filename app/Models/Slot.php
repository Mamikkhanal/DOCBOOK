<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
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
        
        static::creating(function ($slot) {
            $slot->slug = Str::random(16);
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

}
