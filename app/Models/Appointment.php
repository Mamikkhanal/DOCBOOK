<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected  $guarded = [];

    public function doctors(){
        return $this->belongsTo(Doctor::class);
    }

    public function patients(){
        return $this->belongsTo(Patient::class);
    }

    public function services(){
        return $this->belongsTo(Service::class);
    }
}
