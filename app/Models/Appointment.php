<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
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
        
        static::creating(function ($appointment) {
            $appointment->slug = Str::random(16);
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function slot(){
        return $this->belongsTo(Slot::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
    
    public function review(){
        return $this->hasOne(Review::class);
    }
}
