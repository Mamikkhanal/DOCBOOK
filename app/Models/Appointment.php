<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected  $guarded = [];
    protected $casts = [
        'date' => 'datetime:d-m-Y',
        'start_time' => 'datetime:H:i', // Cast to time format
        'end_time' => 'datetime:H:i',
    ];
    

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function slot(){
        return $this->hasOne(Slot::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
    
    public function review(){
        return $this->hasOne(Review::class);
    }
}
