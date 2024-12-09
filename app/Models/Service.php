<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected  $guarded = [];

    public function getRouteKeyName(){
        return 'slug';
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }


}
