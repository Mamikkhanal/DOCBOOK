<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected  $guarded = [];


    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            $payment->slug = Str::random(16);
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }
    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
