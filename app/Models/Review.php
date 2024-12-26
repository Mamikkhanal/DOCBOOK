<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected  $guarded = [];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($review) {
            $review->slug = Str::random(16);
        });
        
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
