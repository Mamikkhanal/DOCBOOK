<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentSlotFactory> */
    use HasFactory;

    protected  $guarded = [];
    
    public function doctors(){
        return $this->belongsTo(AppointmentSlot::class);
    }
}