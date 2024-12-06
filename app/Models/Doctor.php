<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected  $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
    public function specializations(){
        return $this->hasOne(Specialization::class);
    }
}