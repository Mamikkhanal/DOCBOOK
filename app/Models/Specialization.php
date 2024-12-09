<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected  $guarded = [];

    public function getRouteKeyName(){
        return 'slug';
    }

    public function doctors(){
        return $this->belongsToMany(Doctor::class);
    }
}
