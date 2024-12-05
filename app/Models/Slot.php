<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected  $guarded = [];

    protected $casts = [
        'date' => 'datetime:d-m-Y',
        'start_time' => 'datetime:H:i', // Cast to time format
        'end_time' => 'datetime:H:i',
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
}
